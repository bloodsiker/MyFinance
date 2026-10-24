<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Data;

final class Reply
{
    private function __construct(private readonly array $payload)
    {
    }

    public static function text(string $text): self
    {
        return new self(['text' => $text]);
    }

    public static function fromArray(array $payload): self
    {
        return new self($payload);
    }

    public static function inline(string $text, array $buttons): self
    {
        return new self(['text' => $text, 'buttons' => $buttons]);
    }

    public function withPrefix(string $prefix): self
    {
        return new self(array_replace($this->payload, ['text' => $prefix.$this->payload['text']]));
    }

    public function editing(int $messageId): self
    {
        return new self(array_replace($this->payload, ['edit_message_id' => $messageId]));
    }

    /** Keeps the persisted reply format compatible with already processed updates. */
    public function toArray(): array
    {
        return $this->payload;
    }
}
