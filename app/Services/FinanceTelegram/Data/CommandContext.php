<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Data;

use App\Models\User;

final class CommandContext
{
    public function __construct(
        public readonly TelegramUpdate $update,
        public readonly User $user,
        public readonly Conversation $conversation,
    ) {
    }
}
