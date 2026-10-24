<?php

namespace App\Services\TelegramExchangeBot\Commands;


use App\Services\TelegramExchangeBot\Constants;

class CurrencyFromToCommand extends AbstractCommand implements CommandInterface
{
    /**
     * @var string $name
     */
    public static $name = 'currency_from_to';

    /**
     * @var string $description
     */
    protected $description = 'Показать вторую валюту для выбора направление обмена';

    /**
     * @return void
     */
    public function execute(): void
    {
        $result = $this->getRefreshConfig();
        if ($result) {
            [, $fromCurrency] = explode('|', $this->telegramBot->getMessage());

            $directionList = $result['directions'];
            $currencyList = $result['currencies'];

            foreach ($directionList[$fromCurrency] as $key => &$direction) {
                $currency = $currencyList[$key];
                $direction['type'] = $currency['type'];
            }

            uasort($directionList[$fromCurrency], static function ($a, $b) {
                return -1 * ($a['type'] <=> $b['type']);
            });

            $buttonsInline = [];
            $i = 0;
            foreach ($directionList[$fromCurrency] as $key => $direction) {
                $currency = $currencyList[$key];
                $fromCurrencyName = $currencyList[$fromCurrency];
                $buttonsInline['inline_keyboard'][$i][0]['text'] = sprintf('%s - %s', $fromCurrencyName['name'], $currency['name']);
                $buttonsInline['inline_keyboard'][$i][0]['callback_data'] = Constants::COMMAND_DIRECTION_INFO.'|'.sprintf('%s|%s', $fromCurrency, $key);
                $i++;
            }
            $buttonsInline['inline_keyboard'][$i][0]['text'] = '⬅️ Назад';
            $buttonsInline['inline_keyboard'][$i][0]['callback_data'] = Constants::COMMAND_BACK_TO_FROM;

            $this->editButtons('🟢 Оберіть напрямок обміну', $buttonsInline);
        } else {
            $this->sendError();
        }
    }
}
