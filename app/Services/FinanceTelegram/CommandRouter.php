<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram;

use App\Models\User;
use App\Services\FinanceTelegram\Contracts\CommandStrategy;
use App\Services\FinanceTelegram\Contracts\ConversationStrategy;
use App\Services\FinanceTelegram\Data\{CommandContext, Conversation, Reply, TelegramUpdate};

final class CommandRouter
{
    /** @var list<CommandStrategy> */
    private array $strategies;

    /** @param iterable<CommandStrategy> $strategies */
    public function __construct(iterable $strategies, private readonly MainMenu $menu)
    {
        $commands = [];
        $conversations = [];
        foreach ($strategies as $strategy) {
            if ($strategy instanceof ConversationStrategy) {
                $conversations[] = $strategy;
            } else {
                $commands[] = $strategy;
            }
        }
        $this->strategies = array_merge($commands, $conversations);
    }

    public function dispatch(TelegramUpdate $update, User $user, Conversation $conversation): Reply
    {
        $context = new CommandContext($update->withText($this->menu->normalize($update->text)), $user, $conversation);
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($context)) {
                return $strategy->handle($context);
            }
        }

        return $this->menu->reply('Оберіть дію. Для допомоги натисніть /help.');
    }
}
