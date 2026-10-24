<?php

namespace App\Service\TelegramExchangeBot\Commands;

use App\Entity\TelegramRequest;
use App\Service\Processing;
use App\Service\TelegramExchangeBot\Constants;

class OrderCreateCommand extends AbstractCommand implements CommandInterface
{
    /**
     * @var string $name
     */
    public static $name = 'order_create';

    /**
     * @var string $description
     */
    protected $description = 'Отправляем данные в процессинг для создание заказа';

    /**
     * @return false|void
     */
    public function execute()
    {
        $result = $this->getRefreshConfig();
        $processing = $this->telegramBot->getProcessing();
        $emailRepository = $this->telegramBot->getTelegramContainer()->getEmailRepository();
        $emailHelper = $this->telegramBot->getTelegramContainer()->getEmailHelper();
        $emailSenderService = $this->telegramBot->getTelegramContainer()->getEmailSenderService();
        $em = $this->telegramBot->getTelegramContainer()->getContainer()->get('doctrine.orm.entity_manager');
        $twig = $this->telegramBot->getTelegramContainer()->getContainer()->get('twig');

        if ($result) {

            $buttonsInline = ['inline_keyboard' => []];
            $this->telegramBot->editButtons($this->telegramBot->getText(), $buttonsInline, $this->telegramBot->getMessageId());

            [, $requestId] = explode('|', $this->telegramBot->getMessage());

            $repository = $em->getRepository(TelegramRequest::class);
            /** @var TelegramRequest $telegramRequest */
            $telegramRequest = $repository->find($requestId);

            if (!$telegramRequest) {
                $this->sendError();
                return false;
            }

            if ($telegramRequest->getStatus() !== Constants::STATUS_FINISH) {
                $telegramRequest->setStatus(Constants::STATUS_ORDER);
                $telegramRequest->setCommand($this->getName());

                $em->persist($telegramRequest);
                $em->flush();

                $output = '♻️ <b>Зачекайте, створюється заявка</b>';
                $this->sendMessage($output);

                $inputData = json_decode($telegramRequest->getNotes(), true);
                $userEmail = null;

                foreach ($inputData as $value) {
                    if (mb_strpos($value['name'], 'city') === 0) {
                        $orderData['city'] = $value['value'];
                    } else {
                        $orderData[$value['name']] = $value['value'];
                    }

                    if (mb_strpos($value['name'], 'email') === 0) {
                        $userEmail = $value['value'];
                    }
                }

                $orderData['sum_from'] = $telegramRequest->getSumFrom();
                $orderData['sum_to']   = $telegramRequest->getSumTo();

                $orderData['currency_from'] = $telegramRequest->getCurrencyFrom();
                $orderData['currency_to'] = $telegramRequest->getCurrencyTo();

                $orderData['ip']      = $this->telegramBot->getContainer()->get('request_stack')->getCurrentRequest()->getClientIp();
                $orderData['browser'] = 'Telegram';

                $user = $processing->getProcessingUser(
                    $telegramRequest->getTelegramUserId(),
                    $userEmail,
                    Processing::USER_SOURCE_TRUSTEE,
                    $telegramRequest->getTelegramUsername()
                );
                $orderData['user_id'] = $user ? $user['frontId'] : null;

                $orderData['referrer'] = null;
                $orderData['site'] = Constants::SITE;
                $orderData['lang'] = $this->telegramBot->getLocal();

                $response = $processing->createOrder($orderData);

                if ($response['status'] === 'success') {
                    if ($response['aml_check'] && !empty($orderData['email'])) {
                        $currencies = $processing->getCurrencies();
                        $template = $response['commission'] ? 'create_order_to_coin' : 'create_order';

                        $emailTemplate = $emailRepository->getTemplate(
                            $this->telegramBot->getLocal(),
                            $template,
                            $response['currency_from_type'],
                            $response['currency_to_type']
                        );
                        $generalTemplate = $emailRepository->getTemplate(
                            $this->telegramBot->getLocal(),
                            'general_content',
                            $response['currency_from_type'],
                            $response['currency_to_type']
                        );

                        if ($emailTemplate && $generalTemplate) {
                            $emailSenderService->sendEmail(
                                $orderData['email'],
                                $emailTemplate->getTitle(),
                                $twig->render('email/general_email.html.twig', array_merge(
                                    $emailHelper->getDefaultVars(), [
                                    'general_content' => $generalTemplate->getContent(),
                                    'content' => $emailTemplate->getContent(),
                                    'id' => $response['change_request_id'],
                                    'in_sum' => $response['sum_from'],
                                    'out_sum' => $response['sum_to'],
                                    'in_currency' => $currencies[$response['currency_from']]['name'],
                                    'out_currency' => $currencies[$response['currency_to']]['name'],
                                    'in_currency_type' => $response['currency_from_type'],
                                    'out_currency_type' => $response['currency_to_type'],
                                    'cards' => $response['cards'],
                                    'commission' => $response['commission'],
                                    'secret_order' => $response['secret_order'],
                                    'sum_to_without_commission' => $response['sum_to_without_commission'],
                                    'city' => (!empty($orderData['city']) ? $this->getCity($orderData['city']) : '')
                                ]))
                            );
                        }
                    }

                    $paymentAddress = $this->getHandlerToPayment($response);

                    $statusUrl = sprintf('%s/%s/order/status/%s', $this->telegramBot->getFrontHost(), $this->telegramBot->getLocal(), $response['secret_order']);

                    $htmlSuccess = sprintf('📥 <b>Заявка №%s</b>', $response['change_request_id']);
                    $htmlSuccess .= PHP_EOL . PHP_EOL . '🕒 Очікується оплата';

                    if (isset($paymentAddress['address'])) {
                        $htmlSuccess .= PHP_EOL . PHP_EOL . sprintf('💳 Переведіть рівно %s %s на гаманець для оплати ⬇️',
                                $paymentAddress['sum'], $response['currency_from']);
                    }

                    if ($response['payin_source'] === Constants::SOURCE_CROSSPAY) {
                        $htmlSuccess .= PHP_EOL . PHP_EOL . '‼️ З метою боротьби з шахраями, посилання на форму оплати доступне тільки зі сторінки статусу.️';
                    }

//                    $htmlSuccess .= PHP_EOL . PHP_EOL . '🌐 Продовжити обмін на сторінці:';
//                    $htmlSuccess .= PHP_EOL . sprintf('<a href="%s">%s</a>', $statusUrl, $statusUrl);

                    $this->sendMessage($htmlSuccess);

                    $buttonsInline['inline_keyboard'][0][0]['text'] = 'Перейти';
                    $buttonsInline['inline_keyboard'][0][0]['url'] = $statusUrl;
                    $this->sendButtons('🌐 Продовжити обмін на сторінці:', $buttonsInline);

                    if (isset($paymentAddress['address'])) {
                        $this->sendMessage($paymentAddress['address']);
                    }

                    // Send status request
                    $statusMessage = '⚙️ Статус заявки';
                    $statusMessage .= PHP_EOL . PHP_EOL . 'Очікуємо оплату по заявці';
                    $this->sendMessage($statusMessage);

                    $telegramRequest->setMessageId($this->telegramBot->getLastSendMessageId());

                    $telegramRequest->setStatus(Constants::STATUS_FINISH);
                    $telegramRequest->setChangeRequestId($response['change_request_id']);

                    $em->persist($telegramRequest);
                    $em->flush();

                } else {
                    $this->sendError('🚫 Виникла помилка під час створення заявки');
                }
            }
        } else {
            $this->sendError();
        }
    }

    /**
     * @param $data
     *
     * @return mixed|null
     */
    protected function getHandlerToPayment($data)
    {
        $host = $this->telegramBot->getFrontHost();

        switch ($data['payin_source']) {
            case Constants::SOURCE_WHITEBIT:
                $url = $host . '/payin_whitebit_order';
                $address = $this->telegramBot->curlRequest($url, ['id' => $data['change_request_id']]);
                $this->telegramBot->log(sprintf('[%s] Create crypto address %s (%s): %s', $data['change_request_id'], $data['payin_source'], $url, json_encode($address)));
                break;
            case Constants::SOURCE_KUNA:
                $url = $host . '/payin_kuna_order';
                $address = $this->telegramBot->curlRequest($url, ['id' => $data['change_request_id']]);
                $this->telegramBot->log(sprintf('[%s] Create crypto address %s (%s): %s', $data['change_request_id'], $data['payin_source'], $url, json_encode($address)));
                break;
            default:
                $this->telegramBot->log(sprintf('[%s] Endpoint not found for address generation %s', $data['change_request_id'], $data['payin_source']));
                $address = null;
        }

        return $address;
    }

    public function getCity($name)
    {
        $city = $this->telegramBot->getTelegramContainer()->getLangHelper()->getCity(
            $this->telegramBot->getTelegramContainer()->getProcessing()->getCities(),
            $name,
            $this->telegramBot->getLocal()
        );

        return $city ? $city['name'] : '';
    }
}