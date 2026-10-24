<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Strategies;

use App\Models\Category;
use App\Models\MonthCategory;
use App\Models\Transaction;
use App\Services\FinanceTelegram\Contracts\ConversationStrategy;
use App\Services\FinanceTelegram\Data\{CommandContext, Conversation, ExpenseStage, Reply};
use App\Services\FinanceTelegram\{MainMenu, MonthSelector, TextFormatter};

final class ExpenseStrategy implements ConversationStrategy
{
    private const CALLBACK_PATTERN = '/^(category|pay|save):([a-f0-9]{16})(?::(.+))?$/';
    private const CANCEL_BUTTON = [['text' => 'Скасувати', 'callback_data' => 'cancel']];

    public function __construct(
        private readonly MainMenu $menu,
        private readonly MonthSelector $months,
        private readonly TextFormatter $formatter,
    ) {
    }

    public function supports(CommandContext $context): bool
    {
        return $context->update->isAction('add')
            || (bool) preg_match(self::CALLBACK_PATTERN, $context->update->callback)
            || ($context->update->callback === '' && in_array($context->conversation->stage(), [ExpenseStage::Description, ExpenseStage::Amount], true));
    }

    public function handle(CommandContext $context): Reply
    {
        if ($context->update->isAction('add')) {
            return $this->start($context);
        }
        if (preg_match(self::CALLBACK_PATTERN, $context->update->callback, $parts)) {
            if (!$context->conversation->matchesFlow($parts[2])) {
                return $this->menu->reply('Ця кнопка вже неактуальна. Почніть нову витрату через /add.');
            }

            $action = $parts[1];
            $argument = $parts[3] ?? null;
            $expectedStage = match ($action) {
                'category' => ExpenseStage::Category,
                'pay' => ExpenseStage::Payment,
                'save' => ExpenseStage::Confirmation,
            };
            if ($context->conversation->stage() !== $expectedStage
                || ($action === 'pay' && !in_array($argument, ['card', 'cash'], true))) {
                return $this->menu->reply('Цей крок уже завершено. Продовжіть поточне додавання або натисніть /cancel.');
            }

            return match ($action) {
                'category' => $this->category($context->conversation, $argument),
                'pay' => $this->payment($context, $argument),
                'save' => $this->save($context),
            };
        }

        return match ($context->conversation->stage()) {
            ExpenseStage::Description => $this->description($context->conversation, $context->update->text),
            ExpenseStage::Amount => $this->amount($context->conversation, $context->update->text),
            default => $this->menu->reply('Оберіть дію. Для допомоги натисніть /help.'),
        };
    }

    private function start(CommandContext $context): Reply
    {
        $month = $this->months->selected($context->conversation);
        if (!$month) {
            return $this->menu->reply('Спочатку створіть рік у кабінеті.');
        }
        $categories = MonthCategory::with('category')->where('month_id', $month->id)->get();
        if ($categories->isEmpty()) {
            return $this->menu->reply('Додайте бюджет хоча б однієї категорії в цьому місяці через кабінет.');
        }

        $context->conversation->beginExpense((int) $month->id);
        $flow = $context->conversation->value('flow');
        $buttons = $categories->filter(fn (MonthCategory $row): bool => $row->category !== null)
            ->map(fn (MonthCategory $row): array => [
                'text' => $row->category->name,
                'callback_data' => 'category:'.$flow.':'.$row->category_id,
            ])->chunk(2)->map(fn ($chunk): array => $chunk->values()->all())->all();
        $buttons[] = self::CANCEL_BUTTON;

        return Reply::inline($this->formatter->monthLabel($month)."\nОберіть категорію витрати:", $buttons);
    }

    private function category(Conversation $conversation, ?string $categoryId): Reply
    {
        $category = MonthCategory::where('month_id', $conversation->monthId())->where('category_id', $categoryId)->first();
        if (!$category) {
            return $this->menu->reply('Категорія недоступна. Почніть заново через /add.');
        }
        $conversation->transition(ExpenseStage::Description, ['category_id' => $category->category_id]);

        return Reply::text('Напишіть опис витрати (до 255 символів). /cancel — скасувати.');
    }

    private function description(Conversation $conversation, string $text): Reply
    {
        if ($text === '') {
            return $this->menu->reply('Оберіть дію. Для допомоги натисніть /help.');
        }
        if (mb_strlen($text) > 255 || str_starts_with($text, '/')) {
            return Reply::text('Введіть опис від 1 до 255 символів або /cancel.');
        }
        $conversation->transition(ExpenseStage::Amount, ['title' => $text]);

        return Reply::text('Введіть суму в гривнях, наприклад 250 або 250,50.');
    }

    private function amount(Conversation $conversation, string $text): Reply
    {
        if (!preg_match('/^(?:0|[1-9]\d{0,9})(?:[.,]\d{1,2})?$/', $text) || (float) str_replace(',', '.', $text) <= 0) {
            return Reply::text('Потрібна додатна сума з точністю до копійок, наприклад 250,50.');
        }
        $conversation->transition(ExpenseStage::Payment, ['amount' => str_replace(',', '.', $text)]);
        $flow = $conversation->value('flow');

        return Reply::inline('Як оплатили?', [
            [
                ['text' => 'Картка', 'callback_data' => 'pay:'.$flow.':card'],
                ['text' => 'Готівка', 'callback_data' => 'pay:'.$flow.':cash'],
            ],
            self::CANCEL_BUTTON,
        ]);
    }

    private function payment(CommandContext $context, string $type): Reply
    {
        $month = $this->months->selected($context->conversation);
        if (!$month) {
            return $this->unavailable($context->conversation);
        }
        $context->conversation->transition(ExpenseStage::Confirmation, ['type' => $type]);
        $category = Category::find($context->conversation->value('category_id'));
        $lines = [
            'Перевірте витрату:',
            $this->formatter->monthLabel($month),
            'Категорія: '.optional($category)->name,
            'Опис: '.$context->conversation->value('title'),
            'Сума: '.$this->formatter->money($context->conversation->value('amount')).' грн',
            'Оплата: '.($type === 'card' ? 'Картка' : 'Готівка'),
        ];

        return Reply::inline(implode("\n", $lines), [
            [['text' => 'Зберегти транзакцію', 'callback_data' => 'save:'.$context->conversation->value('flow')]],
            self::CANCEL_BUTTON,
        ]);
    }

    private function save(CommandContext $context): Reply
    {
        $conversation = $context->conversation;
        if (!MonthCategory::where('month_id', $conversation->monthId())->where('category_id', $conversation->value('category_id'))->exists()) {
            return $this->unavailable($conversation);
        }
        Transaction::create([
            'month_id' => $conversation->monthId(),
            'category_id' => $conversation->value('category_id'),
            'user_id' => $context->user->id,
            'title' => $conversation->value('title'),
            'amount' => $conversation->value('amount'),
            'type' => $conversation->value('type'),
            'created_at' => now(),
        ]);
        $conversation->reset();

        return $this->menu->reply('Транзакцію збережено. Вона вже доступна в кабінеті.');
    }

    private function unavailable(Conversation $conversation): Reply
    {
        $conversation->reset();

        return $this->menu->reply('Категорія або місяць більше недоступні. Транзакцію не збережено.');
    }
}
