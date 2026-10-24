<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Strategies;

use App\Models\Category;
use App\Models\Transaction;
use App\Services\FinanceTelegram\Contracts\CommandStrategy;
use App\Services\FinanceTelegram\Data\{CommandContext, Reply};
use App\Services\FinanceTelegram\{MainMenu, TextFormatter};
use Carbon\Carbon;

final class TodayStrategy implements CommandStrategy
{
    public function __construct(private readonly MainMenu $menu, private readonly TextFormatter $formatter)
    {
    }

    public function supports(CommandContext $context): bool
    {
        return $context->update->isAction('today');
    }

    public function handle(CommandContext $context): Reply
    {
        $timezone = config('app.timezone', 'UTC');
        $day = Carbon::now($timezone)->startOfDay();
        $query = Transaction::where('created_at', '>=', $day)->where('created_at', '<', $day->copy()->addDay());
        $total = (clone $query)->count();
        $spent = (float) (clone $query)->sum('amount');
        $lines = [
            'Витрати сьогодні · '.$day->format('d.m.Y'),
            'Транзакцій: '.$total,
            'Разом: '.$this->formatter->money($spent).' грн',
            '',
        ];
        if (!$total) {
            return $this->menu->reply(implode("\n", $lines).'Сьогодні ще немає витрат.');
        }

        $lines[] = 'За категоріями:';
        $sums = (clone $query)->selectRaw('category_id, SUM(amount) as total')->groupBy('category_id')->pluck('total', 'category_id');
        $categories = Category::whereIn('id', $sums->keys())->pluck('name', 'id');
        foreach ($sums as $id => $amount) {
            $lines[] = ($categories[$id] ?? 'Без категорії').': '.$this->formatter->money($amount).' грн';
        }
        $lines[] = '';
        $lines[] = $total > 10 ? 'Останні 10 транзакцій:' : 'Транзакції:';
        $transactions = $query->orderByDesc('created_at')->orderByDesc('id')->limit(10)->get();
        foreach ($transactions as $row) {
            $lines[] = $row->created_at->copy()->setTimezone($timezone)->format('H:i').' · '
                .mb_strimwidth($row->title, 0, 90, '…').' — '.$this->formatter->money($row->amount).' грн';
        }

        return $this->menu->reply(implode("\n", $lines));
    }
}
