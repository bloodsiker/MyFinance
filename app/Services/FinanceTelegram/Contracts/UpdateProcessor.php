<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Contracts;

use App\Models\User;
use App\Services\FinanceTelegram\Data\Reply;
use App\Services\FinanceTelegram\Data\TelegramUpdate;

interface UpdateProcessor
{
    public function process(TelegramUpdate $update, User $user, bool $linkedNow): Reply;
}
