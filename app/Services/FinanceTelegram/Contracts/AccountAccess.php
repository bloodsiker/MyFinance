<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Contracts;

use App\Models\User;

interface AccountAccess
{
    public function resolve(string $telegramId): ?User;

    /** @return array{ok: bool, message?: string, user?: User} */
    public function consume(string $code, string $telegramId): array;
}
