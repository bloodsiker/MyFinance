<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\FinanceTelegram\Contracts\TelegramTransport;
use App\Services\FinanceTelegram\Data\Reply;
use App\Services\FinanceTelegram\MainMenu;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramTransportTest extends TestCase
{
    public function test_long_unicode_reply_is_split_and_keyboard_attached_only_to_last_chunk(): void
    {
        Http::fake(['*' => Http::response(['ok' => true])]);
        $reply = (new MainMenu())->reply(str_repeat('я', 3900));
        app(TelegramTransport::class)->send('123', $reply);
        $requests = Http::recorded()->map(fn ($entry) => $entry[0]);
        $this->assertCount(2, $requests);
        $this->assertSame(3800, mb_strlen($requests[0]['text']));
        $this->assertArrayNotHasKey('reply_markup', $requests[0]->data());
        $this->assertTrue($requests[1]['reply_markup']['is_persistent']);
        $this->assertSame(str_repeat('я', 3900), $requests[0]['text'].$requests[1]['text']);
    }

    public function test_unchanged_edit_is_successful_without_sending_a_duplicate_message(): void
    {
        Http::fake(['*' => Http::response(['ok' => false, 'description' => 'Bad Request: message is not modified'], 400)]);
        app(TelegramTransport::class)->send('123', Reply::text('Page')->editing(50));
        $this->assertCount(1, Http::recorded());
        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/editMessageText'));
    }

    public function test_uneditable_message_falls_back_to_a_new_message(): void
    {
        Http::fakeSequence()->push(['ok' => false], 400)->push(['ok' => true]);
        app(TelegramTransport::class)->send('123', Reply::text('Page')->editing(50));
        $this->assertCount(2, Http::recorded());
        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/sendMessage') && $request['text'] === 'Page');
    }
}
