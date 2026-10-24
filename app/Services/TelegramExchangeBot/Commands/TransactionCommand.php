<?php

namespace App\Services\TelegramExchangeBot\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class TransactionCommand extends AbstractCommand implements CommandInterface
{
    public const BLACK_CARD = 'esJrkUs7YhRzbGE-Vq4zuw';

    /**
     * @return void
     */
    public function execute(): void
    {
        $transactions = $this->getTransaction();
        if (isset($balances['errorDescription'])) {
            $output = '<b>🚫 Помилка, багато запитів за 1 хв</b>' . PHP_EOL;

            $this->sendMessage($output);
        }

        $periods = [];
        foreach ($transactions as $transaction) {
            $date = Carbon::createFromTimestamp($transaction['time']);
            $formattedDate = $date->format('Y-m-d');
            if ($transaction['amount'] < 0 && $transaction['id'] !== 'yRiCtZMtme4bz_VouA') {
                $periods[$formattedDate][] = [
                    'description' => $transaction['description'],
                    'amount' => $transaction['amount'] / 100,
                ];
            }
            if ($transaction['amount'] < 0 && ($transaction['id'] === '2nZ5Rg6tnBudZrycaw')) {
                $periods[$formattedDate][] = [
                    'description' => $transaction['description'],
                    'amount' => 3060,
                ];
            }
        }

        $periods = array_reverse($periods);

        foreach ($periods as $day => $period) {
            $output = '📅 ' . $day . PHP_EOL . PHP_EOL;
            $totalAmount = 0;
            foreach ($period as $transaction) {
                $output .= "[" . number_format($transaction['amount'], 2, ',', ' ') ." грн] - <b>{$transaction['description']}</b>" . PHP_EOL;
                $totalAmount = bcadd($totalAmount, $transaction['amount']);
            }
            $output .= PHP_EOL . "✅ <b>Сума за день: </b>" . number_format($totalAmount, 2, ',', ' ') . ' грн';
            $this->sendMessage($output);
        }
    }

    protected function getTransaction($from = null, $to = null)
    {
        $from = (new \DateTime())->modify('-7 days')->getTimestamp();

        $url = sprintf('https://api.monobank.ua/personal/statement/%s/%s', self::BLACK_CARD, $from);
        if ($to) {
            $url = sprintf('https://api.monobank.ua/personal/statement/%s/%s/%s', self::BLACK_CARD, $from, $to);
        }

        $response = Http::withHeaders([
            'X-Token' => 'uvZ6cJ1MQgiGeU15Sd48nN9WyG32H8T8HaAx92jtv0rQ',
        ])->get($url);

        $responseBody = $response->body();

        return json_decode($responseBody, true);
    }
}
