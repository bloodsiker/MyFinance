<?php

namespace App\Services\TelegramExchangeBot\Commands;


use App\Services\TelegramExchangeBot\Constants;
use App\Services\TelegramExchangeBot\TelegramBot;

class DirectionInformationCommand extends AbstractCommand implements CommandInterface
{
    /**
     * @var string $name
     */
    public static $name = 'direction_information';

    /**
     * @var string $description
     */
    protected $description = 'Показать информацию по обмену, курс и кнопку для начала обмена';

    /**
     * @return void
     */
    public function execute(): void
    {
        $result = $this->getRefreshConfig();
        $processing = $this->telegramBot->getProcessing();
        if ($result) {
            $currencyList =  $result['currencies'];

            [, $from, $to] = explode('|', $this->telegramBot->getMessage());

            $operationInfo = $processing->getOperationInfo($from, $to, 100, 0);

            $currencyFrom = $currencyList[$from];
            $currencyTo = $currencyList[$to];

            $output = sprintf('♻️ Обмін <b>%s</b> на <b>%s</b> ❔', $currencyFrom['name'], $currencyTo['name']);
            $output .= PHP_EOL . PHP_EOL . '<b>Курс:</b>';
            $output .= PHP_EOL . sprintf('🟢 1 %s = %s %s', $currencyFrom['name'], $this->getCourseBySum($operationInfo['countedSumTo'], 100, $currencyTo['accuracy']), $currencyTo['name']);
            $output .= PHP_EOL . sprintf('🟢 10 %s = %s %s', $currencyFrom['name'], $this->getCourseBySum($operationInfo['countedSumTo'], 10, $currencyTo['accuracy']), $currencyTo['name']);
            $output .= PHP_EOL . sprintf('🟢 100 %s = %s %s', $currencyFrom['name'], $this->getCourseBySum($operationInfo['countedSumTo'], 1, $currencyTo['accuracy']), $currencyTo['name']);
            $output .= PHP_EOL . PHP_EOL . sprintf('<b>Резерв:</b> %s %s', $currencyTo['balance'], $currencyTo['name']);

            $buttonsInline = [
                'inline_keyboard' => [
                    [
                        ['text' => sprintf('✏️ Вказати кількість %s для обміну', $currencyFrom['name']), 'callback_data' => Constants::COMMAND_REQUEST_CREATE.'|'.sprintf('%s|%s', $from, $to)],
                    ],
                    [
                        ['text' => '⬅️ Назад', 'callback_data' => Constants::COMMAND_CURRENCY_FROM.'|'.$from]
                    ]
                ]
            ];

            $this->telegramBot->setRedisKey(Constants::LAST_MESSAGE_DIRECTION_INFO_ID, $this->telegramBot->getMessageId());

            $this->editButtons($output, $buttonsInline);
        } else {
            $this->sendError();
        }
    }

    /**
     * @param $sumTo
     * @param $sum
     * @param $accuracy
     *
     * @return float
     */
    public function getCourseBySum($sumTo, $sum, $accuracy)
    {
        return round($sumTo / $sum, $accuracy);
    }
}
