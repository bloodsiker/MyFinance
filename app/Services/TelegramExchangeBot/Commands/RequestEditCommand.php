<?php

namespace App\Service\TelegramExchangeBot\Commands;

use App\Entity\TelegramRequest;
use App\Service\TelegramExchangeBot\Constants;

class RequestEditCommand extends AbstractCommand implements CommandInterface
{
    /**
     * @var string $name
     */
    public static $name = 'request_edit';

    /**
     * @var string $description
     */
    protected $description = 'Форма редактирования данных пользователя';

    /**
     * @return false|void
     */
    public function execute()
    {
        $result = $this->getRefreshConfig();
        $em = $this->telegramBot->getContainer()->get('doctrine.orm.entity_manager');
        if ($result) {

            $buttonsInline = ['inline_keyboard' => []];
            $this->telegramBot->editButtons($this->telegramBot->getText(), $buttonsInline, $this->telegramBot->getMessageId());

            $directionList = $result['directions'];
            $currencyList =  $result['currencies'];

            [, $requestId] = explode('|', $this->telegramBot->getMessage());

            $repository = $em->getRepository(TelegramRequest::class);
            $telegramRequest = $repository->find($requestId);

            if (!$telegramRequest) {
                $this->sendError();
                return false;
            }

            $currencyFrom = $currencyList[$telegramRequest->getCurrencyFrom()];
            $directionCurrencyTo = $directionList[$telegramRequest->getCurrencyFrom()][$telegramRequest->getCurrencyTo()];

            if ($telegramRequest->getCommand() !== $this->getName()) {
                $telegramRequest->setStatus(Constants::STATUS_ACTIVE);
                $telegramRequest->setCommand($this->getName());
                $telegramRequest->setStep('amount');
                $telegramRequest->setNotes('');

                $em->persist($telegramRequest);
                $em->flush();
            }

            $this->telegramBot->setRedisKey(Constants::NEXT_COMMAND, RequestUpdateCommand::$name);

            $output = sprintf('✏️ Вкажіть кількість %s яку бажаєте обміняти', $currencyFrom['name']);
            $output .= PHP_EOL . PHP_EOL . sprintf('<b>Мінімальна сума обміну</b> - %s %s', $directionCurrencyTo['min_sum'], $directionCurrencyTo['currencyFromName']);
            $output .= PHP_EOL . sprintf('<b>Максимальна сума обміну</b> - %s %s', $directionCurrencyTo['max_sum'], $directionCurrencyTo['currencyFromName']);

            $this->sendMessage($output);
        } else {
            $this->sendError();
        }
    }
}