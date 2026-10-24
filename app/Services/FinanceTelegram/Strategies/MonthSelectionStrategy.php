<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Strategies;

use App\Models\Month;
use App\Models\Year;
use App\Services\FinanceTelegram\Contracts\CommandStrategy;
use App\Services\FinanceTelegram\Data\{CommandContext, Reply};
use App\Services\FinanceTelegram\MainMenu;
use App\Services\FinanceTelegram\Reports\MonthlyReport;

final class MonthSelectionStrategy implements CommandStrategy
{
    public function __construct(private readonly MainMenu $menu, private readonly MonthlyReport $report)
    {
    }

    public function supports(CommandContext $context): bool
    {
        return $context->update->isAction('month', 'years')
            || (bool) preg_match('/^(year|month):\d+$/', $context->update->callback);
    }

    public function handle(CommandContext $context): Reply
    {
        if ($context->update->isAction('month', 'years')) {
            $context->conversation->reset();
            $buttons = Year::orderByDesc('name')->get()->map(fn (Year $year): array => [
                ['text' => (string) $year->name, 'callback_data' => 'year:'.$year->id],
            ])->all();

            return Reply::inline($buttons ? 'Оберіть рік:' : 'Спочатку створіть рік у кабінеті.', $buttons);
        }

        if (preg_match('/^year:(\d+)$/', $context->update->callback, $match)) {
            $buttons = Month::where('year_id', $match[1])->orderByRaw('CAST(number AS UNSIGNED)')->get()
                ->map(fn (Month $month): array => ['text' => $month->name, 'callback_data' => 'month:'.$month->id])
                ->chunk(3)->map(fn ($chunk): array => $chunk->values()->all())->all();

            return Reply::inline('Оберіть місяць:', $buttons);
        }

        preg_match('/^month:(\d+)$/', $context->update->callback, $match);
        $month = Month::find($match[1]);
        if (!$month) {
            return $this->menu->reply('Цього місяця вже немає. Оберіть інший.');
        }
        $context->conversation->reset((int) $month->id);

        return $this->report->build($month);
    }
}
