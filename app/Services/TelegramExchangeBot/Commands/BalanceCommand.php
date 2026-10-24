<?php

namespace App\Services\TelegramExchangeBot\Commands;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BalanceCommand extends AbstractCommand implements CommandInterface
{
    /**
     * @return void
     */
    public function execute(): void
    {
        $balances = $this->getBalance();
        Log::info('Telegram Webhook:' . print_r($balances, 1));
        die;
        if (isset($balances['errorDescription'])) {
            $output = '<b>🚫 Помилка, багато запитів</b>' . PHP_EOL;

            $this->sendMessage($output);
        }

        $amount = 0;
        foreach ($balances['accounts'] as $balance) {
            if ($balance['id'] === 'esJrkUs7YhRzbGE-Vq4zuw') {
                $amount = $balance['balance'] / 100;
                break;
            }
        }
        $output = '<b>💳 Баланс: </b>' . number_format($amount, 2, ',', ' ') . ' грн' . PHP_EOL;

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
