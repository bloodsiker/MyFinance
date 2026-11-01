<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Data;

final class Conversation
{
    public function __construct(private array $state = [])
    {
        if (!empty($state['expires']) && $state['expires'] < time()) {
            $this->reset();
        }
    }

    public function monthId(): ?int
    {
        return isset($this->state['month_id']) ? (int) $this->state['month_id'] : null;
    }

    public function reset(?int $monthId = null): void
    {
        $this->state = ['month_id' => $monthId ?? $this->monthId()];
    }

    public function beginExpense(?int $monthId = null): void
    {
        $this->state = array_filter([
            'month_id' => $monthId,
            'flow' => bin2hex(random_bytes(8)),
            'step' => ($monthId ? ExpenseStage::Category : ExpenseStage::Year)->value,
            'expires' => time() + 1800,
        ], static fn (mixed $value): bool => $value !== null);
    }

    public function stage(): ?ExpenseStage
    {
        return ExpenseStage::tryFrom($this->state['step'] ?? '');
    }

    public function transition(ExpenseStage $stage, array $values = []): void
    {
        $this->state = array_replace($this->state, $values, ['step' => $stage->value]);
    }

    public function value(string $key): mixed
    {
        return $this->state[$key] ?? null;
    }

    public function matchesFlow(string $flow): bool
    {
        return isset($this->state['flow']) && hash_equals($this->state['flow'], $flow);
    }

    public function toArray(): array
    {
        return $this->state;
    }
}
