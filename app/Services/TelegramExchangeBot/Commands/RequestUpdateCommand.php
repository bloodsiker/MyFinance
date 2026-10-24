<?php

namespace App\Service\TelegramExchangeBot\Commands;

use App\Entity\TelegramRequest;
use App\Service\TelegramExchangeBot\Constants;

class RequestUpdateCommand extends AbstractCommand implements CommandInterface
{
    /**
     * @var string $name
     */
    public static $name = 'request_update';

    /**
     * @var string $description
     */
    protected $description = 'Форма заполнение данными пользователя';

    /**
     * @return false|void
     */
    public function execute()
    {
        $processing = $this->telegramBot->getProcessing();
        $result = $this->getRefreshConfig();
        $em = $this->telegramBot->getContainer()->get('doctrine.orm.entity_manager');
        if ($result) {
            $directionList = $result['directions'];
            $currencyList =  $result['currencies'];

            $repository = $em->getRepository(TelegramRequest::class);
            $telegramRequest = $repository->findOneBy([
                'status' => 'active',
                'chatId' => $this->telegramBot->getChatId(),
            ], ['id' => 'DESC']);

            if (!$telegramRequest) {
                $this->sendError();
                return false;
            }

            $directionInfo = $processing->getDirectionInfo($telegramRequest->getCurrencyFrom(), $telegramRequest->getCurrencyTo());
            if ($directionInfo['status'] === 'error') {
                $this->sendMessage('❌ ' . $directionInfo['message']);
                return false;
            }

            $fields = $directionInfo['contact_form']['fields'];

            // Формируем поля которые нужно заполнит клиенту
            $formFields['amount']['name'] = 'amount';
            $formFields['amount']['displayName'] = 'Cума обміну';
            foreach ($fields as $key => $field) {
                if ($field['required']) {
                    $formFields[$key]['name'] = $field['name'];
                    $formFields[$key]['mask'] = $field['mask'];
                    $formFields[$key]['validator'] = $field['validator'];
                    $formFields[$key]['displayName'] = $field['displayName'];
                    $formFields[$key]['errorMessage'] = $field['errorMessage'];
                }
            }

            $directionCurrencyTo = $directionList[$telegramRequest->getCurrencyFrom()][$telegramRequest->getCurrencyTo()];

            // Validation amount
            if ($telegramRequest->getStep() === 'amount') {
                $amount = $this->telegramBot->getMessage();
                if (!is_numeric($amount)) {
                    $this->sendMessage('❌ Введіть коректну суму (число або число з плаваючою точкою)');
                    return false;
                }

                if ((float) $amount < (float) $directionCurrencyTo['min_sum']) {
                    $this->sendMessage(
                       sprintf('❌ Мінімальна сума обміну - %s %s', $directionCurrencyTo['min_sum'], $directionInfo['currency_from']['name'])
                    );
                    return false;
                }
                if ((float) $amount > (float) $directionCurrencyTo['max_sum']) {
                    $this->sendMessage(
                        sprintf('❌ Максимальна сума обміну - %s %s', $directionCurrencyTo['max_sum'], $directionInfo['currency_from']['name'])
                    );
                    return false;
                }

                $telegramRequest->setSumFrom($this->telegramBot->getMessage());

                $operationInfo = $processing->getOperationInfo($telegramRequest->getCurrencyFrom(), $telegramRequest->getCurrencyTo(), $telegramRequest->getSumFrom(), 0);
                $currencyTo = $currencyList[$telegramRequest->getCurrencyTo()];
                $currencyInToFrom = round($operationInfo['countedSumTo'], $currencyTo['accuracy']);

                $telegramRequest->setSumTo($currencyInToFrom);
                $telegramRequest->setCommand($this->getName());

                $message = sprintf('➡️ Віддаєте %s %s', $telegramRequest->getSumFrom(), $directionInfo['currency_from']['name']);
                $message .= PHP_EOL . sprintf('⬅️ Отримуєте %s %s', $currencyInToFrom, $directionInfo['currency_to']['name']);
                $this->sendMessage($message);
            }

            // Validation fields
            $currentStep = $formFields[$telegramRequest->getStep()];
            if ($currentStep && isset($currentStep['validator']) && $currentStep['validator']) {
                if (stripos($currentStep['name'], 'card_number') !== false) {
                    if (!preg_match("/^\d{16}$/", $this->telegramBot->getMessage())) {
                        $msg = $this->telegramBot->getTranslator()->trans($currentStep['errorMessage']);
                        $this->sendMessage(sprintf('❌ %s', $msg));
                        return false;
                    }
                } else {
                    if (!preg_match($currentStep['validator'], $this->telegramBot->getMessage())) {
                        $msg = $this->telegramBot->getTranslator()->trans($currentStep['errorMessage']);
                        $this->sendMessage(sprintf('❌ %s', $msg));
                        return false;
                    }
                }
            }

            $countInput = count($formFields);
            $formFieldsKey = array_keys($formFields);
            $step = array_search($telegramRequest->getStep(), $formFieldsKey);
            $nextStepIndex = $step + 1;

            if ($nextStepIndex < $countInput) {
                $nextStepKey = $formFieldsKey[$nextStepIndex];
                $nextStep = $formFields[$nextStepKey];

                if (stripos($currentStep['name'], 'card_number') !== false) {
                    $splitArray = str_split($this->telegramBot->getMessage(), 4);
                    $message = implode('-', $splitArray);
                } else {
                    $message = $this->telegramBot->getMessage();
                }

                $notes = json_decode($telegramRequest->getNotes(), true);

                $notes[] = [
                    'name' => $currentStep['name'],
                    'displayName' => $currentStep['displayName'],
                    'value' => $message,
                ];

                $telegramRequest->setStep($nextStep['name']);
                $telegramRequest->setNotes(json_encode($notes));
                $em->persist($telegramRequest);
                $em->flush();

                $this->telegramBot->setRedisKey(Constants::NEXT_COMMAND, $this->getName());

                $stepMessage = sprintf('<b>📌 Введіть %s</b>', $nextStep['displayName']);
                if (isset($nextStep['mask']) && $nextStep['mask']) {
                    if (stripos($nextStep['name'], 'phone') !== false) {
                        $stepMessage .= PHP_EOL . 'Приклад: +38 (000) 000-00-00';
                    } else if (stripos($nextStep['name'], 'card_number') !== false) {
                        $stepMessage .= PHP_EOL . 'Приклад: 0000000000000000';
                    } else {
                        $stepMessage .= PHP_EOL . 'Приклад: ' . $nextStep['mask'];
                    }
                }

                $this->sendMessage($stepMessage);

            } else {
               // Пишем последний шаг
                if (stripos($currentStep['name'], 'card_number') !== false) {
                    $splitArray = str_split($this->telegramBot->getMessage(), 4);
                    $message = implode('-', $splitArray);
                } else {
                    $message = $this->telegramBot->getMessage();
                }

                $notes = json_decode($telegramRequest->getNotes(), true);
                $notes[] = [
                    'name' => $currentStep['name'],
                    'displayName' => $currentStep['displayName'],
                    'value' => $message,
                ];

                $telegramRequest->setNotes(json_encode($notes));
                $em->persist($telegramRequest);
                $em->flush();

                $output = '✏️🗒 <b>Дані заявки:</b>';
                $output .= PHP_EOL . PHP_EOL . sprintf(
                    '✅ <b>Cума обміну</b>: %s %s на %s %s',
                    $telegramRequest->getSumFrom(),
                    $telegramRequest->getCurrencyFrom(),
                    $telegramRequest->getSumTo(),
                    $telegramRequest->getCurrencyTo()
                    );
                foreach ($notes as $note) {
                    if ($note['name'] !== 'amount') {
                        $output .= PHP_EOL  . sprintf('✅ <b>%s</b> - %s', $note['displayName'], $note['value']);
                    }
                }

                $buttonsInline = [
                    'inline_keyboard' => [
                        [
                            ['text' => '👍 Створити заявку', 'callback_data' => Constants::COMMAND_ORDER_CREATE.'|'.$telegramRequest->getId()],
                        ],
                        [
                            ['text' => '✏️️ Редагувати', 'callback_data' => Constants::COMMAND_REQUEST_EDIT.'|'.$telegramRequest->getId()]
                        ]
                    ]
                ];

                $this->telegramBot->deleteRedisKey(Constants::NEXT_COMMAND);
                $this->sendButtons($output, $buttonsInline);
            }

        } else {
            $this->sendError();
        }
    }
}