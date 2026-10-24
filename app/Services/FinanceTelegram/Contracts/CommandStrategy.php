<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Contracts;

use App\Services\FinanceTelegram\Data\CommandContext;
use App\Services\FinanceTelegram\Data\Reply;

interface CommandStrategy
{
    public function supports(CommandContext $context): bool;

    public function handle(CommandContext $context): Reply;
}
