<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Reports;

use App\Models\{Category, Month, MonthCategory, Transaction};
use App\Services\FinanceTelegram\Data\Reply;
use App\Services\FinanceTelegram\MainMenu;
use App\Services\FinanceTelegram\TextFormatter;

final class MonthlyReport
{
    public function __construct(private readonly MainMenu $menu, private readonly TextFormatter $formatter)
    {
    }

    public function build(Month $month): Reply
    {
        $budget = (float) MonthCategory::where('month_id', $month->id)->sum('budget');
        $spent = (float) Transaction::where('month_id', $month->id)->sum('amount');
        $lines = [
            $this->formatter->monthLabel($month),
            'Бюджет: '.$this->formatter->money($budget).' грн',
            'Витрачено: '.$this->formatter->money($spent).' грн',
            ($spent > $budget ? '🔴 Перевищення: ' : 'Залишок: ').$this->formatter->money(abs($budget - $spent)).' грн',
            '',
            'За категоріями:',
        ];
        $sums = Transaction::where('month_id', $month->id)
            ->selectRaw('category_id, SUM(amount) as total')->groupBy('category_id')->pluck('total', 'category_id');
        $plans = MonthCategory::where('month_id', $month->id)->pluck('budget', 'category_id');
        $categoryIds = $sums->keys()->merge($plans->keys())->unique();

        foreach (Category::whereIn('id', $categoryIds)->orderBy('name')->get() as $category) {
            $plan = (float) ($plans[$category->id] ?? 0);
            $actual = (float) ($sums[$category->id] ?? 0);
            $lines[] = ($actual > $plan ? '🔴 ' : '🔵 ').$category->name.': '
                .$this->formatter->money($actual).' / '.$this->formatter->money($plan).' грн';
        }

        return $this->menu->reply(implode("\n", $lines));
    }
}
