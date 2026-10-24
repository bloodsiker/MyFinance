<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\FinanceTelegram\AccountLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{DB, Http};
use Tests\TestCase;

class TelegramAccountLinkTest extends TestCase
{
    use RefreshDatabase;
    private $owner;
    private $other;

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('roles')->insert(['id' => 1, 'name' => 'User', 'role' => 'user']);
        $this->owner = User::create(['name' => 'Owner', 'email' => 'owner@example.test', 'password' => 'not-used', 'role_id' => 1, 'last_url' => '/']);
        $this->other = User::create(['name' => 'Other', 'email' => 'other@example.test', 'password' => 'not-used', 'role_id' => 1, 'last_url' => '/']);
        config(['finance-telegram.token' => 'test-token', 'finance-telegram.username' => 'finance_test_bot', 'finance-telegram.webhook_secret' => 'test-secret', 'finance-telegram.users' => []]);
        Http::fake(['*' => Http::response(['ok' => true, 'result' => []])]);
    }

    private function code(int $id): string
    {
        $reply = $this->postJson('/cabinet-api/users/'.$id.'/telegram-link')->assertOk();
        parse_str(parse_url($reply->json('url'), PHP_URL_QUERY), $params);
        $this->assertLessThanOrEqual(64, strlen($params['start']));
        return substr($params['start'], 5);
    }

    private function start(string $code, int $chat = 987654, int $updateId = 1)
    {
        return $this->withHeader('X-Telegram-Bot-Api-Secret-Token', 'test-secret')->postJson('/telegram/webhook', [
            'update_id' => $updateId, 'message' => ['from' => ['id' => $chat], 'chat' => ['id' => $chat, 'type' => 'private'], 'text' => '/start link_'.$code],
        ])->assertOk();
    }

    public function test_user_list_restricts_account_management_and_hides_credentials(): void
    {
        $response = $this->actingAs($this->owner)->getJson('/cabinet-api/users')->assertOk()->assertJsonCount(2, 'users')->assertJsonPath('can_manage_users', true);
        $this->assertArrayNotHasKey('password', $response->json('users.0'));
        $this->actingAs($this->other)->getJson('/cabinet-api/users')->assertOk()->assertJsonCount(1, 'users')->assertJsonPath('users.0.id', $this->other->id)->assertJsonPath('can_manage_users', false);
        $this->putJson('/cabinet-api/users/'.$this->owner->id.'/telegram', ['telegram_id' => '111'])->assertForbidden();
        $this->postJson('/cabinet-api/users/'.$this->owner->id.'/telegram-link')->assertForbidden();
        $this->get('/users')->assertOk()->assertSee('finance-app');
    }

    public function test_manual_link_edit_and_unlink_persist_without_env_changes(): void
    {
        $this->actingAs($this->owner)->putJson('/cabinet-api/users/'.$this->other->id.'/telegram', ['telegram_id' => '123456'])->assertOk();
        $this->assertSame('123456', $this->other->fresh()->telegram_id);
        $this->assertSame($this->other->id, app(AccountLink::class)->resolve('123456')->id);
        $this->putJson('/cabinet-api/users/'.$this->other->id.'/telegram', ['telegram_id' => null])->assertOk();
        $this->assertNull(app(AccountLink::class)->resolve('123456'));
    }

    public function test_id_must_be_numeric_and_unique(): void
    {
        $this->actingAs($this->owner);
        $this->putJson('/cabinet-api/users/'.$this->owner->id.'/telegram', ['telegram_id' => '123456'])->assertOk();
        $this->putJson('/cabinet-api/users/'.$this->other->id.'/telegram', ['telegram_id' => '123456'])->assertUnprocessable();
        foreach (['@username', '-100123', '123.45', '1e5'] as $id) $this->putJson('/cabinet-api/users/'.$this->other->id.'/telegram', ['telegram_id' => $id])->assertUnprocessable();
    }

    public function test_unlink_disables_legacy_env_mapping(): void
    {
        config(['finance-telegram.users' => ['123456' => $this->other->id]]);
        $this->assertNotNull(app(AccountLink::class)->resolve('123456'));
        $this->actingAs($this->other)->putJson('/cabinet-api/users/'.$this->other->id.'/telegram', ['telegram_id' => null])->assertOk();
        $this->assertNull(app(AccountLink::class)->resolve('123456'));
    }

    public function test_start_link_automatically_binds_user_and_is_retry_safe(): void
    {
        $this->actingAs($this->other);
        $code = $this->code($this->other->id);
        $this->assertDatabaseHas('finance_telegram_links', ['token_hash' => hash('sha256', $code), 'user_id' => $this->other->id]);
        $this->start($code);
        $this->assertSame('987654', $this->other->fresh()->telegram_id);
        $this->assertSame($this->other->id, app(AccountLink::class)->resolve('987654')->id);
        $this->start($code);
        $this->start($code, 987654, 2);
        $this->assertSame('987654', $this->other->fresh()->telegram_id);
        Http::assertSent(fn ($request) => str_contains($request['text'] ?? '', 'успішно прив’язано'));
    }

    public function test_used_code_cannot_bind_a_second_telegram_account(): void
    {
        $this->actingAs($this->other);
        $code = $this->code($this->other->id);
        $this->start($code, 987654, 1);
        $this->start($code, 111222, 2);
        $this->assertSame('987654', $this->other->fresh()->telegram_id);
        $this->assertNull(app(AccountLink::class)->resolve('111222'));
    }

    public function test_expired_and_replaced_codes_do_not_bind(): void
    {
        $this->actingAs($this->other);
        $old = $this->code($this->other->id);
        $current = $this->code($this->other->id);
        $this->start($old);
        $this->assertNull($this->other->fresh()->telegram_id);
        $this->travel(11)->minutes();
        $this->start($current, 987654, 2);
        $this->assertNull($this->other->fresh()->telegram_id);
    }

    public function test_existing_binding_cannot_be_taken_over_by_another_user(): void
    {
        $this->actingAs($this->owner)->putJson('/cabinet-api/users/'.$this->owner->id.'/telegram', ['telegram_id' => '987654'])->assertOk();
        $this->actingAs($this->other);
        $code = $this->code($this->other->id);
        $this->start($code);
        $this->assertSame('987654', $this->owner->fresh()->telegram_id);
        $this->assertNull($this->other->fresh()->telegram_id);
    }

    public function test_manual_change_invalidates_old_connect_links(): void
    {
        $this->actingAs($this->other);
        $code = $this->code($this->other->id);
        $this->putJson('/cabinet-api/users/'.$this->other->id.'/telegram', ['telegram_id' => '555666'])->assertOk();
        $this->start($code);
        $this->assertSame('555666', $this->other->fresh()->telegram_id);
    }

    public function test_bot_username_is_discovered_without_exposing_token_to_browser(): void
    {
        config(['finance-telegram.username' => null]);
        Http::swap(new \Illuminate\Http\Client\Factory());
        Http::fake(['*/getMe' => Http::response(['ok' => true, 'result' => ['username' => 'discovered_bot']])]);
        $this->actingAs($this->other)->postJson('/cabinet-api/users/'.$this->other->id.'/telegram-link')->assertOk()->assertJsonMissing(['token' => 'test-token'])->assertJsonStructure(['url', 'expires_at']);
        $this->assertDatabaseCount('finance_telegram_links', 1);
    }
}
