<?php

declare(strict_types=1);

namespace Tests\Unit\FinanceTelegram;

use App\Services\FinanceTelegram\Data\{Conversation, ExpenseStage, Reply, TelegramUpdate};
use PHPUnit\Framework\TestCase;

class ConversationTest extends TestCase
{
    public function test_old_persisted_draft_resumes_with_typed_stage(): void
    {
        $conversation = new Conversation(['month_id' => 46, 'step' => 'description', 'flow' => '0123456789abcdef', 'expires' => time() + 300]);
        $this->assertSame(ExpenseStage::Description, $conversation->stage());
        $this->assertTrue($conversation->matchesFlow('0123456789abcdef'));
        $conversation->transition(ExpenseStage::Amount, ['title' => 'Test']);
        $this->assertSame('amount', $conversation->toArray()['step']);
        $this->assertSame('Test', $conversation->value('title'));
        $this->assertSame(46, $conversation->monthId());
    }

    public function test_expired_draft_is_cleared_but_selected_month_is_kept(): void
    {
        $conversation = new Conversation(['month_id' => 46, 'step' => 'confirm', 'amount' => '50', 'expires' => time() - 1]);
        $this->assertSame(['month_id' => 46], $conversation->toArray());
        $this->assertNull($conversation->stage());
    }

    public function test_new_expense_invalidates_previous_flow_and_reset_preserves_month(): void
    {
        $conversation = new Conversation();
        $conversation->beginExpense(46);
        $oldFlow = $conversation->value('flow');
        $conversation->beginExpense(47);
        $this->assertFalse($conversation->matchesFlow($oldFlow));
        $this->assertSame(ExpenseStage::Category, $conversation->stage());
        $conversation->reset();
        $this->assertSame(['month_id' => 47], $conversation->toArray());
    }

    public function test_reply_transformations_do_not_mutate_cached_payload(): void
    {
        $payload = ['text' => 'Existing reply', 'buttons' => [[['text' => 'Next', 'callback_data' => 'next']]]];
        $original = Reply::fromArray($payload);
        $edited = $original->withPrefix('Prefix: ')->editing(50);
        $this->assertSame($payload, $original->toArray());
        $this->assertSame('Prefix: Existing reply', $edited->toArray()['text']);
        $this->assertSame(50, $edited->toArray()['edit_message_id']);
    }

    /** @dataProvider invalidUpdates */
    public function test_invalid_or_non_private_updates_are_ignored(array $payload): void
    {
        $this->assertNull(TelegramUpdate::fromArray($payload));
    }

    public static function invalidUpdates(): array
    {
        return [
            [[]],
            [['update_id' => 1, 'callback_query' => 'invalid']],
            [['update_id' => 1, 'message' => 'invalid']],
            [['update_id' => 1, 'message' => ['chat' => ['id' => 123, 'type' => 'group'], 'from' => ['id' => 123]]]],
            [['update_id' => 1, 'message' => ['chat' => ['id' => 123, 'type' => 'private'], 'from' => ['id' => 456]]]],
            [['update_id' => 1, 'message' => ['chat' => ['id' => 123, 'type' => 'private'], 'from' => ['id' => 123], 'text' => ['invalid']]]],
        ];
    }
}
