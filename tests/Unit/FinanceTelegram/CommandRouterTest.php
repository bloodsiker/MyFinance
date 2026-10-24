<?php

declare(strict_types=1);

namespace Tests\Unit\FinanceTelegram;

use App\Models\User;
use App\Services\FinanceTelegram\CommandRouter;
use App\Services\FinanceTelegram\Contracts\CommandStrategy;
use App\Services\FinanceTelegram\Data\{CommandContext, Conversation, Reply, TelegramUpdate};
use App\Services\FinanceTelegram\MainMenu;
use PHPUnit\Framework\TestCase;

class CommandRouterTest extends TestCase
{
    public function test_new_strategy_can_be_added_without_changing_the_router(): void
    {
        $strategy = new class implements CommandStrategy {
            public function supports(CommandContext $context): bool
            {
                return $context->update->isAction('custom');
            }

            public function handle(CommandContext $context): Reply
            {
                return Reply::text('Custom command');
            }
        };
        $router = new CommandRouter([$strategy], new MainMenu());
        $reply = $router->dispatch($this->update('/custom'), new User(), new Conversation());

        $this->assertSame('Custom command', $reply->toArray()['text']);
    }

    public function test_keyboard_labels_are_normalized_before_matching_strategies(): void
    {
        $strategy = new class implements CommandStrategy {
            public function supports(CommandContext $context): bool
            {
                return $context->update->isAction('today');
            }

            public function handle(CommandContext $context): Reply
            {
                return Reply::text($context->update->text);
            }
        };
        $router = new CommandRouter([$strategy], new MainMenu());
        $reply = $router->dispatch($this->update('📅 Витрати сьогодні'), new User(), new Conversation());

        $this->assertSame('/today', $reply->toArray()['text']);
    }

    public function test_unknown_command_returns_keyboard_without_modifying_conversation(): void
    {
        $conversation = new Conversation(['month_id' => 46]);
        $router = new CommandRouter([], new MainMenu());
        $reply = $router->dispatch($this->update('/unknown'), new User(), $conversation);

        $this->assertArrayHasKey('keyboard', $reply->toArray());
        $this->assertSame(['month_id' => 46], $conversation->toArray());
    }

    public function test_late_registered_command_takes_precedence_over_active_conversation(): void
    {
        $conversationHandler = new class implements \App\Services\FinanceTelegram\Contracts\ConversationStrategy {
            public function supports(CommandContext $context): bool
            {
                return true;
            }

            public function handle(CommandContext $context): Reply
            {
                return Reply::text('Conversation input');
            }
        };
        $extension = new class implements CommandStrategy {
            public function supports(CommandContext $context): bool
            {
                return $context->update->isAction('custom');
            }

            public function handle(CommandContext $context): Reply
            {
                return Reply::text('Explicit command');
            }
        };
        $conversation = new Conversation(['month_id' => 46, 'step' => 'description']);
        $router = new CommandRouter([$conversationHandler, $extension], new MainMenu());
        $reply = $router->dispatch($this->update('/custom'), new User(), $conversation);

        $this->assertSame('Explicit command', $reply->toArray()['text']);
        $this->assertSame('description', $conversation->toArray()['step']);
    }

    private function update(string $text): TelegramUpdate
    {
        return new TelegramUpdate(1, '123', '123', $text, '', null, null);
    }
}
