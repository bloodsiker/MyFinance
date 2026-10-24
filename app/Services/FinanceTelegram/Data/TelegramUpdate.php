<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Data;

final class TelegramUpdate
{
    public function __construct(
        public readonly int $id,
        public readonly string $chatId,
        public readonly string $senderId,
        public readonly string $text,
        public readonly string $callback,
        public readonly ?string $callbackQueryId,
        public readonly ?int $messageId,
    ) {
    }

    public static function fromArray(array $payload): ?self
    {
        $callback = $payload['callback_query'] ?? [];
        if (!is_array($callback)) {
            return null;
        }
        $message = $payload['message'] ?? $callback['message'] ?? [];
        if (!is_array($message)) {
            return null;
        }
        $sender = $callback['from'] ?? $message['from'] ?? [];
        $chat = $message['chat'] ?? [];
        $id = $payload['update_id'] ?? null;
        if (!is_array($sender) || !is_array($chat)) {
            return null;
        }
        if (!is_int($id) || $id < 0 || ($chat['type'] ?? null) !== 'private') {
            return null;
        }
        $chatId = $chat['id'] ?? null;
        $senderId = $sender['id'] ?? null;
        if (!is_scalar($chatId) || !is_scalar($senderId)
            || !filter_var($chatId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
            || (string) $senderId !== (string) $chatId) {
            return null;
        }
        if (isset($message['text']) && !is_string($message['text'])) {
            return null;
        }
        if (isset($callback['data']) && !is_string($callback['data'])) {
            return null;
        }

        return new self(
            $id,
            (string) $chat['id'],
            (string) $sender['id'],
            trim((string) ($message['text'] ?? '')),
            (string) ($callback['data'] ?? ''),
            isset($callback['id']) ? (string) $callback['id'] : null,
            isset($message['message_id']) ? (int) $message['message_id'] : null,
        );
    }

    public function withText(string $text): self
    {
        return new self($this->id, $this->chatId, $this->senderId, $text, $this->callback, $this->callbackQueryId, $this->messageId);
    }

    public function isAction(string $command, ?string $callback = null): bool
    {
        return $this->text === '/'.$command || $this->callback === ($callback ?? $command);
    }

    public function linkCode(): ?string
    {
        return preg_match('/^\/start(?:@[A-Za-z0-9_]+)? link_([a-f0-9]{48})$/', $this->text, $match)
            ? $match[1]
            : null;
    }
}
