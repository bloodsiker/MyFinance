<?php

namespace App\Services\TelegramExchangeBot\Commands;

use App\Services\TelegramExchangeBot\Constants;

class CurrencyFromCommand extends AbstractCommand implements CommandInterface
{
    /**
     * @var string $name
     */
    public static $name = 'currency_from';

    /**
     * @var string $description
     */
    protected $description = 'Показать валюту для выбора';

    /**
     * @var string $method
     */
    protected $method = 'sendButtons';

    /**
     * @return void
     */
    public function execute(): void
    {
        $result = $this->getRefreshConfig();
        if ($result) {
            $directionList = $result['directions'];
            $currencyList = $result['currencies'];

            foreach ($directionList as $key => &$direction) {
                $currency = $currencyList[$key];
                $direction['type'] = $currency['type'];
            }

            uasort($directionList, static function ($a, $b) {
                return -1 * ($a['type'] <=> $b['type']);
            });

            $buttonsInline = [];
            $i = 0;
            foreach ($directionList as $key => $direction) {
                $currency = $currencyList[$key];
                $buttonsInline['inline_keyboard'][$i][0]['text'] = $currency['name'];
                $buttonsInline['inline_keyboard'][$i][0]['callback_data'] = Constants::COMMAND_CURRENCY_FROM.'|'.$key;
                $i++;
            }

            if ($this->method === 'sendButtons') {
                $this->sendButtons('🟢 Оберіть валюту яку бажаєте обміняти', $buttonsInline);
            }

            if ($this->method  === 'editButtons') {
                $this->editButtons('🟢 Оберіть валюту яку бажаєте обміняти', $buttonsInline);
            }
        } else {
            $this->sendError();
        }
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setExecuteMethod(string $method)
    {
        $this->method = $method;

        return $this;
    }
}
