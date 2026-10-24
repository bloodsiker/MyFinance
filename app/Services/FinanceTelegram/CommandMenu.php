<?php

namespace App\Services\FinanceTelegram;

use Illuminate\Support\Facades\Http;

class CommandMenu
{
    public function register(): void
    {
        $commands = [
            ['command' => 'start', 'description' => 'Головне меню'],
            ['command' => 'stats', 'description' => 'Статистика вибраного місяця'],
            ['command' => 'transactions', 'description' => 'Транзакції місяця з пагінацією'],
            ['command' => 'today', 'description' => 'Витрати сьогодні'],
            ['command' => 'add', 'description' => 'Додати нову витрату'],
            ['command' => 'month', 'description' => 'Обрати рік та місяць'],
            ['command' => 'overview', 'description' => 'Загальний огляд за всі роки'],
            ['command' => 'cabinet', 'description' => 'Відкрити кабінет'],
            ['command' => 'cancel', 'description' => 'Скасувати додавання витрати'],
            ['command' => 'help', 'description' => 'Допомога'],
        ];
        foreach (['setMyCommands' => ['commands' => $commands], 'setChatMenuButton' => ['menu_button' => ['type' => 'commands']]] as $method => $payload) {
            $response = Http::timeout(10)->post('https://api.telegram.org/bot'.config('finance-telegram.token').'/'.$method, $payload);
            if (!$response->successful() || !$response->json('ok')) throw new \RuntimeException('Unable to register Finance Telegram menu.');
        }
    }
}
