<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram;

use App\Services\FinanceTelegram\Data\Reply;

final class MainMenu
{
    private const ACTIONS = [
        '📊 Статистика місяця' => '/stats',
        '🧾 Транзакції' => '/transactions',
        '📅 Витрати сьогодні' => '/today',
        '➕ Додати витрату' => '/add',
        '🗓 Обрати місяць' => '/month',
        '📈 Загальний огляд' => '/overview',
        '🌐 Відкрити кабінет' => '/cabinet',
        '❌ Скасувати' => '/cancel',
    ];

    public function normalize(string $text): string
    {
        return self::ACTIONS[$text] ?? $text;
    }

    public function reply(string $text): Reply
    {
        $keyboard = array_chunk(array_map(fn (string $label): array => ['text' => $label], array_keys(self::ACTIONS)), 2);

        return Reply::fromArray(['text' => str_replace('\\n', "\n", $text), 'keyboard' => $keyboard]);
    }
}
