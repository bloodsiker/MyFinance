<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Strategies;

use App\Models\Month;
use App\Models\Transaction;
use App\Services\FinanceTelegram\Contracts\CommandStrategy;
use App\Services\FinanceTelegram\Data\{CommandContext, Reply};
use App\Services\FinanceTelegram\{MainMenu, MonthSelector, TextFormatter};

final class TransactionsStrategy implements CommandStrategy
{
    private const PAGE_SIZE = 10;

    public function __construct(
        private readonly MainMenu $menu,
        private readonly MonthSelector $months,
        private readonly TextFormatter $formatter,
    ) {
    }

    public function supports(CommandContext $context): bool
    {
        return $context->update->isAction('transactions')
            || (bool) preg_match('/^transactions:(\d+):(\d{1,9})$/', $context->update->callback);
    }

    public function handle(CommandContext $context): Reply
    {
        $page = 1;
        if (preg_match('/^transactions:(\d+):(\d{1,9})$/', $context->update->callback, $match)) {
            $month = Month::find($match[1]);
            $page = max(1, (int) $match[2]);
        } else {
            $month = $this->months->selected($context->conversation);
        }
        if (!$month) {
            return $this->menu->reply('Цього місяця немає. Оберіть інший через /month.');
        }
        $context->conversation->reset((int) $month->id);
        $reply = $this->build($month, $page);

        return $context->update->callbackQueryId !== null && $context->update->messageId !== null
            ? $reply->editing($context->update->messageId)
            : $reply;
    }

    private function build(Month $month, int $page): Reply
    {
        $query = Transaction::where('month_id', $month->id);
        $total = (clone $query)->count();
        $spent = (float) (clone $query)->sum('amount');
        $lastPage = max(1, (int) ceil($total / self::PAGE_SIZE));
        $page = min($page, $lastPage);
        $items = $query->with(['category', 'user'])->orderByDesc('created_at')->orderByDesc('id')
            ->offset(($page - 1) * self::PAGE_SIZE)->limit(self::PAGE_SIZE)->get();
        $lines = [
            'Транзакції: '.$this->formatter->monthLabel($month),
            'Усього: '.$total.' · '.$this->formatter->money($spent).' грн',
            'Сторінка '.$page.' з '.$lastPage,
            '',
        ];
        foreach ($items as $index => $row) {
            $lines[] = (($page - 1) * self::PAGE_SIZE + $index + 1).'. '.mb_strimwidth($row->title, 0, 90, '…')
                .' — '.$this->formatter->money($row->amount).' грн';
            $lines[] = mb_strimwidth(optional($row->category)->name ?? 'Без категорії', 0, 40, '…')
                .' · '.($row->type === 'card' ? 'Картка' : 'Готівка');
            $lines[] = $row->created_at->copy()->setTimezone(config('app.timezone', 'UTC'))->format('d.m.Y H:i')
                .' · '.mb_strimwidth(optional($row->user)->name ?? '—', 0, 40, '…');
            $lines[] = '';
        }
        $lines[] = $total ? 'На цій сторінці: '.$this->formatter->money($items->sum('amount')).' грн' : 'У цьому місяці ще немає транзакцій.';
        $navigation = [];
        if ($page > 1) {
            $navigation[] = ['text' => '← Назад', 'callback_data' => 'transactions:'.$month->id.':'.($page - 1)];
        }
        if ($page < $lastPage) {
            $navigation[] = ['text' => 'Далі →', 'callback_data' => 'transactions:'.$month->id.':'.($page + 1)];
        }
        $buttons = $navigation ? [$navigation] : [];
        $buttons[] = [['text' => 'Статистика місяця', 'callback_data' => 'stats'], ['text' => 'Обрати місяць', 'callback_data' => 'years']];
        $buttons[] = [['text' => 'Додати витрату', 'callback_data' => 'add']];

        return Reply::inline(implode("\n", $lines), $buttons);
    }
}
