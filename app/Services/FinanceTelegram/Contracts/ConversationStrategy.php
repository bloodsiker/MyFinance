<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Contracts;

/** Contextual input handlers run after explicit commands, including extensions. */
interface ConversationStrategy extends CommandStrategy
{
}
