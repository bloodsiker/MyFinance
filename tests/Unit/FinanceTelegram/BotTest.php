<?php

declare(strict_types=1);

namespace Tests\Unit\FinanceTelegram;

use App\Models\User;
use App\Services\FinanceTelegram\Bot;
use App\Services\FinanceTelegram\Contracts\{AccountAccess, TelegramTransport, UpdateProcessor};
use App\Services\FinanceTelegram\Data\{Reply, TelegramUpdate};
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class BotTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_ignored_update_does_not_touch_accounts_database_or_transport(): void
    {
        $accounts = Mockery::mock(AccountAccess::class);
        $processor = Mockery::mock(UpdateProcessor::class);
        $transport = Mockery::mock(TelegramTransport::class);
        $accounts->shouldNotReceive('resolve', 'consume');
        $processor->shouldNotReceive('process');
        $transport->shouldNotReceive('send', 'acknowledge');

        (new Bot($accounts, $processor, $transport))->handle([]);
    }

    public function test_authorized_update_is_processed_and_delivered_via_interfaces(): void
    {
        $accounts = Mockery::mock(AccountAccess::class);
        $processor = Mockery::mock(UpdateProcessor::class);
        $transport = Mockery::mock(TelegramTransport::class);
        $user = new User();
        $reply = Reply::text('Statistics');
        $accounts->shouldReceive('resolve')->once()->with('123')->andReturn($user);
        $processor->shouldReceive('process')->once()->withArgs(fn (TelegramUpdate $update, User $actor, bool $linked): bool => $update->text === '/stats' && $actor === $user && !$linked)->andReturn($reply);
        $transport->shouldReceive('send')->once()->with('123', $reply);
        $transport->shouldReceive('acknowledge')->once()->with(Mockery::type(TelegramUpdate::class));

        (new Bot($accounts, $processor, $transport))->handle($this->payload('/stats'));
    }

    public function test_failed_link_is_delivered_without_processing_a_command(): void
    {
        $accounts = Mockery::mock(AccountAccess::class);
        $processor = Mockery::mock(UpdateProcessor::class);
        $transport = Mockery::mock(TelegramTransport::class);
        $code = str_repeat('a', 48);
        $accounts->shouldReceive('consume')->once()->with($code, '123')->andReturn(['ok' => false, 'message' => 'Expired']);
        $accounts->shouldNotReceive('resolve');
        $processor->shouldNotReceive('process');
        $transport->shouldReceive('send')->once()->withArgs(fn (string $chatId, Reply $reply): bool => $chatId === '123' && $reply->toArray()['text'] === 'Expired');
        $transport->shouldReceive('acknowledge')->once();

        (new Bot($accounts, $processor, $transport))->handle($this->payload('/start link_'.$code));
    }

    private function payload(string $text): array
    {
        return ['update_id' => 1, 'message' => ['chat' => ['id' => 123, 'type' => 'private'], 'from' => ['id' => 123], 'text' => $text]];
    }
}
