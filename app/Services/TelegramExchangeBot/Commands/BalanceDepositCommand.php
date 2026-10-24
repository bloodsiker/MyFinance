<?php

namespace App\Services\TelegramExchangeBot\Commands;

use Illuminate\Support\Facades\Http;

class BalanceDepositCommand extends AbstractCommand implements CommandInterface
{
    public static $cards = [
        'Массаж',
        'ЧП',
        'Доставка еды',
        'Мобильный *',
        'Кофе Вика',
        'Аптека',
        'Продукты',
        'Вика процедуры *',
        'Никита *',
        'Китай *',
    ];

    /**
     * @return void
     */
    public function execute(): void
    {
        $balances = $this->getBalance();
        if (isset($balances['errorDescription'])) {
            $output = '<b>🚫 Помилка, багато запитів</b>' . PHP_EOL;

            $this->sendMessage($output);
        }

        $balanceDeposits = [];
        foreach ($balances['jars'] as $balance) {
            if (in_array($balance['title'], self::$cards, true)) {
                $balanceDeposits[$balance['id']] = [
                    'title' => $balance['title'],
                    'balance' => $balance['balance'] / 100,
                ];
            }
        }

        $output = '';
        foreach ($balanceDeposits as $deposit) {
            $output .= "[" . number_format($deposit['balance'], 2, ',', ' ') ." грн] - <b>{$deposit['title']}</b>" . PHP_EOL;
        }

        $this->sendMessage($output);
    }

    protected function getBalance()
    {
        $response = Http::withHeaders([
            'X-Token' => 'uvZ6cJ1MQgiGeU15Sd48nN9WyG32H8T8HaAx92jtv0rQ',
        ])->get('https://api.monobank.ua/personal/client-info');

        $responseBody = $response->body();

        return json_decode($responseBody, true);
    }
}
