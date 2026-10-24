<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Strategies;

use App\Services\FinanceTelegram\Contracts\CommandStrategy;
use App\Services\FinanceTelegram\Data\{CommandContext, Reply};
use App\Services\FinanceTelegram\MainMenu;

final class CabinetStrategy implements CommandStrategy
{
    public function __construct(private readonly MainMenu $menu)
    {
    }

    public function supports(CommandContext $context): bool
    {
        return $context->update->isAction('cabinet');
    }

    public function handle(CommandContext $context): Reply
    {
        $url = trim((string) (config('finance-telegram.cabinet_url') ?: config('app.url')));
        if (!filter_var($url, FILTER_VALIDATE_URL) || !in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)) {
            return $this->menu->reply('Адреса кабінету ще не налаштована. Вкажіть FINANCE_TELEGRAM_CABINET_URL у .env.');
        }

        $text = 'Кабінет: '.$url;
        if ($this->isLocal($url)) {
            return $this->menu->reply(
                $text."\n\nЦе локальна адреса: вона відкриється на комп’ютері з кабінетом."
                .' Для доступу з телефона вкажіть публічну адресу в FINANCE_TELEGRAM_CABINET_URL.'
            );
        }

        return Reply::inline($text, [[['text' => 'Відкрити кабінет', 'url' => $url]]]);
    }

    private function isLocal(string $url): bool
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        return !$host || $host === 'localhost' || str_ends_with($host, '.localhost')
            || str_ends_with($host, '.local') || $host === '[::1]'
            || (filter_var($host, FILTER_VALIDATE_IP)
                && !filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE));
    }
}
