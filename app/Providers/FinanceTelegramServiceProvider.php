<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\FinanceTelegram\AccountLink;
use App\Services\FinanceTelegram\CommandRouter;
use App\Services\FinanceTelegram\Contracts\AccountAccess;
use App\Services\FinanceTelegram\Contracts\TelegramTransport;
use App\Services\FinanceTelegram\Contracts\UpdateProcessor;
use App\Services\FinanceTelegram\MainMenu;
use App\Services\FinanceTelegram\Persistence\DatabaseUpdateProcessor;
use App\Services\FinanceTelegram\Strategies\{
    CabinetStrategy,
    ExpenseStrategy,
    MenuStrategy,
    MonthlyStatisticsStrategy,
    MonthSelectionStrategy,
    OverviewStrategy,
    TodayStrategy,
    TransactionsStrategy
};
use App\Services\FinanceTelegram\Transport\HttpTelegramTransport;
use Illuminate\Support\ServiceProvider;

final class FinanceTelegramServiceProvider extends ServiceProvider
{
    public const STRATEGIES = 'finance-telegram.strategies';

    public function register(): void
    {
        $this->app->bind(AccountAccess::class, AccountLink::class);
        $this->app->bind(TelegramTransport::class, HttpTelegramTransport::class);
        $this->app->bind(UpdateProcessor::class, DatabaseUpdateProcessor::class);

        // Explicit commands take precedence over free text in an active expense.
        // New handlers can be registered with this tag without changing Bot/router.
        $strategies = [
            MenuStrategy::class,
            MonthSelectionStrategy::class,
            OverviewStrategy::class,
            TodayStrategy::class,
            CabinetStrategy::class,
            MonthlyStatisticsStrategy::class,
            TransactionsStrategy::class,
            ExpenseStrategy::class,
        ];
        foreach ($strategies as $strategy) {
            $this->app->bind($strategy);
        }
        $this->app->tag($strategies, self::STRATEGIES);
        $this->app->bind(CommandRouter::class, fn ($app): CommandRouter => new CommandRouter(
            $app->tagged(self::STRATEGIES),
            $app->make(MainMenu::class),
        ));
    }
}
