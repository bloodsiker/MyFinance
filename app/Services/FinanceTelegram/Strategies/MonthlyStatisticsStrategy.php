<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Strategies;

use App\Services\FinanceTelegram\Contracts\CommandStrategy;
use App\Services\FinanceTelegram\Data\{CommandContext, Reply};
use App\Services\FinanceTelegram\{MainMenu, MonthSelector};
use App\Services\FinanceTelegram\Reports\MonthlyReport;

final class MonthlyStatisticsStrategy implements CommandStrategy
{
    public function __construct(
        private readonly MonthSelector $months,
        private readonly MonthlyReport $report,
        private readonly MainMenu $menu,
    ) {
    }

    public function supports(CommandContext $context): bool
    {
        return $context->update->isAction('stats');
    }

    public function handle(CommandContext $context): Reply
    {
        $month = $this->months->selected($context->conversation);

        return $month ? $this->report->build($month) : $this->menu->reply('Спочатку створіть рік у кабінеті.');
    }
}
