<?php

namespace Tests\Feature;

use App\Models\{Category, Month, MonthCategory, Transaction, User, Year};
use App\Services\FinanceTelegram\Bot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{DB, Http};
use Tests\TestCase;

class FinanceTelegramTest extends TestCase
{
    use RefreshDatabase;
    private $sequence = 1;
    private $user;
    private $month;
    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        config(['finance-telegram.token' => 'test-token', 'finance-telegram.webhook_secret' => 'test-secret']);
        DB::table('roles')->insert(['id' => 1, 'name' => 'Admin', 'role' => 'admin']);
        $this->user = User::create(['name' => 'Bot User', 'email' => 'bot@example.test', 'password' => 'not-used', 'role_id' => 1, 'last_url' => '/']);
        config(['finance-telegram.users' => ['123456' => $this->user->id]]);
        $year = Year::create(['name' => (string) now()->year]);
        $this->month = Month::create(['year_id' => $year->id, 'name' => 'Тестовий місяць', 'number' => (string) now()->month]);
        $this->category = Category::create(['name' => 'Продукти']);
        MonthCategory::create(['month_id' => $this->month->id, 'category_id' => $this->category->id, 'budget' => 1000]);
        Http::fake(['*' => Http::response(['ok' => true, 'result' => []])]);
    }

    private function update(string $text = '', ?string $callback = null, int $sender = 123456): array
    {
        $message = ['chat' => ['id' => $sender, 'type' => 'private'], 'from' => ['id' => $sender], 'text' => $text];
        return $callback === null ? ['update_id' => $this->sequence++, 'message' => $message] :
            ['update_id' => $this->sequence++, 'callback_query' => ['id' => 'callback-'.$this->sequence, 'from' => ['id' => $sender], 'message' => $message, 'data' => $callback]];
    }

    private function runUpdate(array $update)
    {
        return $this->withHeader('X-Telegram-Bot-Api-Secret-Token', 'test-secret')->postJson('/telegram/webhook', $update)->assertOk();
    }

    private function flow(): string
    {
        return json_decode(DB::table('finance_telegram_chats')->where('chat_id', 123456)->value('state'), true)['flow'];
    }

    private function draft(string $amount = '250,50'): string
    {
        $this->runUpdate($this->update('/add'));
        $flow = $this->flow();
        $this->runUpdate($this->update('', 'category:'.$flow.':'.$this->category->id));
        $this->runUpdate($this->update('Покупка продуктів'));
        $this->runUpdate($this->update($amount));
        $this->runUpdate($this->update('', 'pay:'.$flow.':card'));
        return $flow;
    }

    public function test_webhook_requires_its_secret_and_bot_configuration(): void
    {
        $this->postJson('/telegram/webhook', $this->update('/stats'))->assertForbidden();
        Http::assertNothingSent();
        config(['finance-telegram.token' => null]);
        $this->withHeader('X-Telegram-Bot-Api-Secret-Token', 'test-secret')->postJson('/telegram/webhook', $this->update('/stats'))->assertStatus(503);
    }

    public function test_unlinked_chat_cannot_read_statistics_or_create_expenses(): void
    {
        $this->runUpdate($this->update('/stats', null, 987654));
        Http::assertSent(fn ($request) => $request['chat_id'] === 987654 && str_contains($request['text'], '987654') && !str_contains($request['text'], 'Бюджет:'));
        $this->assertDatabaseCount('transactions', 0);
        $this->assertDatabaseCount('finance_telegram_chats', 0);
    }

    public function test_private_statistics_match_the_cabinet(): void
    {
        Transaction::create(['month_id' => $this->month->id, 'category_id' => $this->category->id, 'user_id' => $this->user->id, 'title' => 'Test', 'amount' => 1200, 'type' => 'card']);
        $this->runUpdate($this->update('/stats'));
        Http::assertSent(fn ($request) => str_contains($request['text'] ?? '', 'Витрачено: 1 200,00 грн') && str_contains($request['text'], 'Перевищення: 200,00 грн') && str_contains($request['text'], 'Продукти: 1 200,00 / 1 000,00 грн'));
    }

    public function test_expense_is_saved_only_after_confirmation_with_correct_author(): void
    {
        $flow = $this->draft();
        $this->assertDatabaseCount('transactions', 0);
        $this->runUpdate($this->update('', 'save:'.$flow));
        $this->assertDatabaseHas('transactions', ['month_id' => $this->month->id, 'category_id' => $this->category->id, 'user_id' => $this->user->id, 'amount' => 250.5, 'type' => 'card', 'title' => 'Покупка продуктів']);
    }

    public function test_retry_and_repeated_confirmation_do_not_duplicate_transactions(): void
    {
        $flow = $this->draft();
        $confirm = $this->update('', 'save:'.$flow);
        $this->runUpdate($confirm);
        $this->runUpdate($confirm);
        $this->runUpdate($this->update('', 'save:'.$flow));
        $this->assertDatabaseCount('transactions', 1);
    }

    public function test_old_buttons_cannot_modify_a_new_draft_and_cancel_does_not_save(): void
    {
        $old = $this->draft();
        $this->runUpdate($this->update('/add'));
        $new = $this->flow();
        $this->assertNotSame($old, $new);
        $this->runUpdate($this->update('', 'save:'.$old));
        $this->assertDatabaseCount('transactions', 0);
        $this->runUpdate($this->update('/cancel'));
        $this->assertArrayNotHasKey('flow', json_decode(DB::table('finance_telegram_chats')->value('state'), true));
    }

    public function test_invalid_amounts_are_not_saved_and_groups_are_ignored(): void
    {
        $this->runUpdate($this->update('/add'));
        $flow = $this->flow();
        $this->runUpdate($this->update('', 'category:'.$flow.':'.$this->category->id));
        $this->runUpdate($this->update('Test'));
        foreach (['-5', '0', '1.123', '1e6', 'abc'] as $amount) $this->runUpdate($this->update($amount));
        $state = json_decode(DB::table('finance_telegram_chats')->value('state'), true);
        $this->assertSame('amount', $state['step']);
        $this->assertDatabaseCount('transactions', 0);
        $before = count(Http::recorded());
        $group = $this->update('/stats'); $group['message']['chat']['type'] = 'group';
        $this->runUpdate($group);
        $this->assertCount($before, Http::recorded());
    }

    public function test_selecting_month_controls_expense_destination(): void
    {
        $other = Month::create(['year_id' => $this->month->year_id, 'name' => 'Інший місяць', 'number' => '1']);
        MonthCategory::create(['month_id' => $other->id, 'category_id' => $this->category->id, 'budget' => 500]);
        $this->runUpdate($this->update('', 'month:'.$other->id));
        $flow = $this->draft('40');
        $this->runUpdate($this->update('', 'save:'.$flow));
        $this->assertDatabaseHas('transactions', ['month_id' => $other->id, 'amount' => 40]);
    }
    public function test_delivery_failure_and_retry_preserve_a_single_saved_expense(): void
    {
        $flow = $this->draft();
        $confirm = $this->update('', 'save:'.$flow);
        Http::swap(new \Illuminate\Http\Client\Factory());
        Http::fakeSequence()->push(['ok' => false], 500)->push(['ok' => true])->push(['ok' => true]);
        $this->withHeader('X-Telegram-Bot-Api-Secret-Token', 'test-secret')->postJson('/telegram/webhook', $confirm)->assertStatus(500);
        $this->assertDatabaseCount('transactions', 1);
        $this->runUpdate($confirm);
        $this->assertDatabaseCount('transactions', 1);
    }

    public function test_local_polling_processes_updates_without_https(): void
    {
        Http::swap(new \Illuminate\Http\Client\Factory());
        Http::fake([
            '*/getWebhookInfo' => Http::response(['ok' => true, 'result' => ['url' => '']]),
            '*/getUpdates*' => Http::response(['ok' => true, 'result' => [$this->update('/stats')]]),
            '*' => Http::response(['ok' => true, 'result' => []]),
        ]);
        $this->artisan('finance:telegram', ['mode' => 'poll', '--once' => true])->assertExitCode(0);
        $this->assertDatabaseCount('finance_telegram_updates', 1);
    }

    public function test_month_transactions_are_paginated_with_totals_and_editable_navigation(): void
    {
        for ($i = 1; $i <= 22; $i++) {
            Transaction::create(['month_id' => $this->month->id, 'category_id' => $this->category->id, 'user_id' => $this->user->id, 'title' => sprintf('Expense%02d', $i), 'amount' => 10, 'type' => 'card']);
        }
        $other = Month::create(['year_id' => $this->month->year_id, 'name' => 'Other', 'number' => '1']);
        Transaction::create(['month_id' => $other->id, 'category_id' => $this->category->id, 'user_id' => $this->user->id, 'title' => 'Outside selected month', 'amount' => 999, 'type' => 'cash']);
        $this->runUpdate($this->update('/transactions'));
        $first = json_decode(DB::table('finance_telegram_updates')->where('update_id', 1)->value('reply'), true);
        $this->assertStringContainsString('Усього: 22 · 220,00 грн', $first['text']);
        $this->assertStringContainsString('Сторінка 1 з 3', $first['text']);
        $this->assertStringContainsString('Expense22', $first['text']);
        $this->assertStringNotContainsString('Expense12', $first['text']);
        $this->assertStringNotContainsString('Outside selected month', $first['text']);
        $this->assertSame('transactions:'.$this->month->id.':2', $first['buttons'][0][0]['callback_data']);
        $next = $this->update('', 'transactions:'.$this->month->id.':2');
        $next['callback_query']['message']['message_id'] = 50;
        $this->runUpdate($next);
        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/editMessageText') && $request['message_id'] === 50 && str_contains($request['text'], 'Сторінка 2 з 3') && str_contains($request['text'], 'Expense12') && !str_contains($request['text'], 'Expense22'));
        $this->runUpdate($this->update('', 'transactions:'.$this->month->id.':999'));
        $last = json_decode(DB::table('finance_telegram_updates')->where('update_id', 3)->value('reply'), true);
        $this->assertStringContainsString('Сторінка 3 з 3', $last['text']);
        $this->assertStringContainsString('На цій сторінці: 20,00 грн', $last['text']);
        $this->assertCount(1, $last['buttons'][0]);
    }

    public function test_transactions_empty_month_and_selection_are_handled(): void
    {
        $other = Month::create(['year_id' => $this->month->year_id, 'name' => 'Інший місяць', 'number' => '1']);
        $this->runUpdate($this->update('', 'month:'.$other->id));
        $this->runUpdate($this->update('/transactions'));
        $reply = json_decode(DB::table('finance_telegram_updates')->where('update_id', 2)->value('reply'), true);
        $this->assertStringContainsString('Інший місяць', $reply['text']);
        $this->assertStringContainsString('У цьому місяці ще немає транзакцій', $reply['text']);
        $this->assertStringContainsString('Сторінка 1 з 1', $reply['text']);
        $this->assertSame('stats', $reply['buttons'][0][0]['callback_data']);
    }

    public function test_today_uses_kyiv_day_boundaries_and_includes_correct_totals(): void
    {
        config(['finance-telegram.timezone' => 'Europe/Kyiv', 'app.timezone' => 'UTC']);
        $this->travelTo(\Carbon\Carbon::parse('2026-10-24 10:00:00', 'UTC'));
        foreach ([['Before', '2026-10-23 20:59:59', 999], ['DayStart', '2026-10-23 21:00:00', 100], ['DayEnd', '2026-10-24 20:59:59', 200], ['Tomorrow', '2026-10-24 21:00:00', 999]] as [$title, $date, $amount]) {
            Transaction::create(['month_id' => $this->month->id, 'category_id' => $this->category->id, 'user_id' => $this->user->id, 'title' => $title, 'created_at' => $date, 'amount' => $amount, 'type' => 'card']);
        }
        $this->runUpdate($this->update('/today'));
        Http::assertSent(fn ($request) => str_contains($request['text'] ?? '', 'Витрати сьогодні · 24.10.2026') && str_contains($request['text'], 'Транзакцій: 2') && str_contains($request['text'], 'Разом: 300,00 грн') && str_contains($request['text'], '00:00 · DayStart') && str_contains($request['text'], '23:59 · DayEnd') && !str_contains($request['text'], 'Before') && !str_contains($request['text'], 'Tomorrow'));
    }

    public function test_today_empty_day_and_cabinet_links(): void
    {
        config(['finance-telegram.cabinet_url' => 'https://finance.example.test']);
        $this->runUpdate($this->update('/today'));
        $reply = json_decode(DB::table('finance_telegram_updates')->where('update_id', 1)->value('reply'), true);
        $this->assertStringContainsString('Сьогодні ще немає витрат', $reply['text']);
        $this->assertSame('🌐 Відкрити кабінет', $reply['keyboard'][3][0]['text']);
        $this->runUpdate($this->update('🌐 Відкрити кабінет'));
        $publicReply = json_decode(DB::table('finance_telegram_updates')->where('update_id', 2)->value('reply'), true);
        $this->assertSame('https://finance.example.test', $publicReply['buttons'][0][0]['url']);
        config(['finance-telegram.cabinet_url' => 'http://localhost:81']);
        $this->runUpdate($this->update('/cabinet'));
        $reply = json_decode(DB::table('finance_telegram_updates')->where('update_id', 3)->value('reply'), true);
        $this->assertStringContainsString('http://localhost:81', $reply['text']);
        $this->assertStringContainsString('Це локальна адреса', $reply['text']);
        $this->assertArrayHasKey('keyboard', $reply);
        $this->assertArrayNotHasKey('buttons', $reply);
    }

    public function test_standard_telegram_menu_registers_today_and_cabinet_commands(): void
    {
        $this->artisan('finance:telegram', ['mode' => 'menu'])->assertExitCode(0);
        Http::assertSent(function ($request) {
            if (!str_ends_with($request->url(), '/setMyCommands')) return false;
            $commands = array_column($request['commands'], 'command');
            return in_array('today', $commands, true) && in_array('cabinet', $commands, true) && in_array('transactions', $commands, true) && in_array('add', $commands, true);
        });
        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/setChatMenuButton') && $request['menu_button']['type'] === 'commands');
    }

    public function test_polling_refuses_a_second_process_before_contacting_telegram(): void
    {
        $path = storage_path('framework/cache/finance-telegram-poll-'.hash('sha256', config('finance-telegram.token')).'.lock');
        $lock = fopen($path, 'c');
        $this->assertTrue(flock($lock, LOCK_EX | LOCK_NB));
        try {
            $this->artisan('finance:telegram', ['mode' => 'poll', '--once' => true])->assertExitCode(1);
            Http::assertNothingSent();
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    public function test_start_shows_persistent_block_keyboard_and_buttons_run_actions(): void
    {
        $this->runUpdate($this->update('/start'));
        Http::assertSent(function ($request) {
            $markup = $request['reply_markup'] ?? [];
            return isset($markup['keyboard']) && count($markup['keyboard']) === 4 &&
                $markup['keyboard'][0][0]['text'] === '📊 Статистика місяця' &&
                $markup['keyboard'][3][0]['text'] === '🌐 Відкрити кабінет' &&
                $markup['resize_keyboard'] === true && $markup['is_persistent'] === true && $markup['one_time_keyboard'] === false;
        });
        $this->runUpdate($this->update('📅 Витрати сьогодні'));
        $today = json_decode(DB::table('finance_telegram_updates')->where('update_id', 2)->value('reply'), true);
        $this->assertStringContainsString('Витрати сьогодні', $today['text']);
        $this->runUpdate($this->update('🗓 Обрати місяць'));
        $months = json_decode(DB::table('finance_telegram_updates')->where('update_id', 3)->value('reply'), true);
        $this->assertSame('Оберіть рік:', $months['text']);
        $this->runUpdate($this->update('❌ Скасувати'));
        $cancel = json_decode(DB::table('finance_telegram_updates')->where('update_id', 4)->value('reply'), true);
        $this->assertArrayHasKey('keyboard', $cancel);
    }

}
