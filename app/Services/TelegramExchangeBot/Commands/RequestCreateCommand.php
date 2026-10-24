<?php

namespace App\Service\TelegramExchangeBot\Commands;

use App\Entity\TelegramRequest;
use App\Service\TelegramExchangeBot\Constants;
use App\Service\TelegramExchangeBot\TelegramBot;

class RequestCreateCommand extends AbstractCommand implements CommandInterface
{
    /**
     * @var string $name
     */
    public static $name = 'request_create';

    /**
     * @var string $description
     */
    protected $description = 'Создание формы для заполнение данными пользователя';

    /**
     * @return void
     */
    public function execute(): void
    {
        $em = $this->telegramBot->getContainer()->get('doctrine.orm.entity_manager');

        $result = $this->getRefreshConfig();

        if ($result) {

            if ($prevMessageId = $this->telegramBot->getRedisKey(Constants::LAST_MESSAGE_DIRECTION_INFO_ID)) {
                $buttonsInline = ['inline_keyboard' => []];
                $this->telegramBot->editButtons($this->telegramBot->getText(), $buttonsInline, $prevMessageId);
                $this->telegramBot->deleteRedisKey(Constants::LAST_MESSAGE_DIRECTION_INFO_ID);
            }

            $directionList = $result['directions'];
            $currencyList = $result['currencies'];

            [, $from, $to] = explode('|', $this->telegramBot->getMessage());

            $currencyFrom = $currencyList[$from];
            $directionCurrencyTo = $directionList[$from][$to];

            $telegramRequest = new TelegramRequest();
            $telegramRequest->setStatus(Constants::STATUS_ACTIVE);
            $telegramRequest->setChatId($this->telegramBot->getChatId());
            $telegramRequest->setTelegramUserId($this->telegramBot->getUser()->id);
            $telegramRequest->setTelegramUsername($this->telegramBot->getUser()->username);
            $telegramRequest->setCommand($this->getName());
            $telegramRequest->setStep('amount');
            $telegramRequest->setCurrencyFrom($from);
            $telegramRequest->setCurrencyTo($to);

            $em->persist($telegramRequest);
            $em->flush();

            $this->telegramBot->setRedisKey(Constants::NEXT_COMMAND, RequestUpdateCommand::$name);

            $output = sprintf('✏️ Вкажіть кількість %s яку бажаєте обміняти', $currencyFrom['name']);
            $output .= PHP_EOL . PHP_EOL . sprintf('<b>Мінімальна сума обміну</b> - %s %s', $directionCurrencyTo['min_sum'], $directionCurrencyTo['currencyFromName']);
            $output .= PHP_EOL . sprintf('<b>Максимальна сума обміну</b> - %s %s', $directionCurrencyTo['max_sum'], $directionCurrencyTo['currencyFromName']);

            $this->telegramBot->sendMessage($output);

        } else {
            $this->sendError();
        }
    }
}