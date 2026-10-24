<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Contracts;

use App\Services\FinanceTelegram\Data\Reply;
use App\Services\FinanceTelegram\Data\TelegramUpdate;

interface TelegramTransport
{
    public function send(string $chatId, Reply $reply): void;

    public function acknowledge(TelegramUpdate $update): void;
}
