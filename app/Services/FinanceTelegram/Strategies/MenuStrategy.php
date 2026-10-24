<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Strategies;

use App\Services\FinanceTelegram\Contracts\CommandStrategy;
use App\Services\FinanceTelegram\Data\{CommandContext, Reply};
use App\Services\FinanceTelegram\MainMenu;

final class MenuStrategy implements CommandStrategy
{
    public function __construct(private readonly MainMenu $menu)
    {
    }

    public function supports(CommandContext $context): bool
    {
        return in_array($context->update->text, ['/start', '/help', '/cancel'], true)
            || $context->update->callback === 'cancel';
    }

    public function handle(CommandContext $context): Reply
    {
        $context->conversation->reset();
        if ($context->update->isAction('cancel')) {
            return $this->menu->reply('Додавання скасовано.');
        }

        return $this->menu->reply(implode("\n", [
            'Finance: статистика та витрати вашого кабінету.',
            '/stats — місячна статистика',
            '/transactions — транзакції за місяць',
            '/today — витрати сьогодні',
            '/cabinet — відкрити кабінет',
            '/month — обрати місяць',
            '/add — додати витрату',
            '/overview — загальний огляд',
            '/cancel — скасувати',
        ]));
    }
}
