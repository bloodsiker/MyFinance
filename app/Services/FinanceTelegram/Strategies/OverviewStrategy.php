<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Strategies;

use App\Models\MonthCategory;
use App\Models\Transaction;
use App\Services\FinanceTelegram\Contracts\CommandStrategy;
use App\Services\FinanceTelegram\Data\{CommandContext, Reply};
use App\Services\FinanceTelegram\{MainMenu, TextFormatter};

final class OverviewStrategy implements CommandStrategy
{
    public function __construct(private readonly MainMenu $menu, private readonly TextFormatter $formatter)
    {
    }

    public function supports(CommandContext $context): bool
    {
        return $context->update->isAction('overview');
    }

    public function handle(CommandContext $context): Reply
    {
        $budget = (float) MonthCategory::sum('budget');
        $spent = (float) Transaction::sum('amount');

        return $this->menu->reply(implode("\n", [
            'Огляд за всі роки',
            'Бюджет: '.$this->formatter->money($budget).' грн',
            'Витрачено: '.$this->formatter->money($spent).' грн',
            ($spent > $budget ? 'Перевищення: ' : 'Залишок: ').$this->formatter->money(abs($budget - $spent)).' грн',
        ]));
    }
}
