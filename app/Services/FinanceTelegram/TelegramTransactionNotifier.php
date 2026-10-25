<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram;

use App\Models\Transaction;
use Illuminate\Support\Facades\{DB, Http, Log};

final class TelegramTransactionNotifier
{
    public function notifySubscribers(Transaction $transaction): void
    {
        $token = (string) config('finance-telegram.token');
        if ($token === '') {
            return;
        }

        try {
            $transaction->loadMissing(['category', 'user']);
            $chats = DB::table('finance_telegram_chats')
                ->join('users', 'users.id', '=', 'finance_telegram_chats.user_id')
                ->where('users.telegram_notifications_enabled', true)
                ->select('finance_telegram_chats.chat_id')
                ->distinct()
                ->get();
        } catch (\Throwable) {
            Log::warning('Telegram transaction subscribers could not be loaded.', [
                'transaction_id' => $transaction->id,
            ]);
            return;
        }

        if ($chats->isEmpty()) {
            return;
        }

        $message = $this->format($transaction);
        foreach ($chats as $chat) {
            try {
                $response = Http::timeout(5)->post(
                    'https://api.telegram.org/bot'.$token.'/sendMessage',
                    ['chat_id' => (int) $chat->chat_id, 'text' => $message]
                );

                if (!$response->successful() || !$response->json('ok')) {
                    Log::warning('Telegram transaction notification was not delivered.', [
                        'transaction_id' => $transaction->id,
                        'chat_id' => $chat->chat_id,
                        'http_status' => $response->status(),
                    ]);
                }
            } catch (\Throwable) {
                Log::warning('Telegram transaction notification request failed.', [
                    'transaction_id' => $transaction->id,
                    'chat_id' => $chat->chat_id,
                ]);
            }
        }
    }

    private function format(Transaction $transaction): string
    {
        $time = $transaction->created_at
            ->copy()
            ->setTimezone(config('app.timezone', 'UTC'))
            ->format('d.m.Y H:i');

        return "Нова транзакція в кабінеті\n"
            .'Опис: '.$transaction->title."\n"
            .'Категорія: '.(optional($transaction->category)->name ?? 'Без категорії')."\n"
            .'Сума: '.number_format((float) $transaction->amount, 2, ',', ' ')." грн\n"
            .'Оплата: '.($transaction->type === 'card' ? 'Картка' : 'Готівка')."\n"
            .'Автор: '.(optional($transaction->user)->name ?? 'Користувач')."\n"
            .'Дата: '.$time;
    }
}
