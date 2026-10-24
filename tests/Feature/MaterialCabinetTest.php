<?php

namespace Tests\Feature;

use App\Models\{Category, Month, Task, Transaction, User, Year};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{DB, Hash, Http};
use Tests\TestCase;

class MaterialCabinetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.debug' => false]);
        DB::table('roles')->insert(['id' => 1, 'name' => 'Admin', 'role' => 'admin']);
    }

    private function user(): User
    {
        return User::create(['name' => 'Test', 'email' => 'test@example.test', 'password' => Hash::make('secret123'), 'role_id' => 1, 'last_url' => '/']);
    }

    private function month(): Month
    {
        $year = Year::create(['name' => '2026']);
        return Month::create(['year_id' => $year->id, 'name' => 'Жовтень', 'number' => '10']);
    }

    public function test_cabinet_and_login_load_only_the_new_frontend(): void
    {
        $this->get('/login')->assertOk()->assertSee('material/app.css')->assertDontSee('dist/css/app.css')->assertDontSee('jquery');
        $this->actingAs($this->user())->get('/')->assertOk()->assertSee('finance-app')->assertDontSee('mix-manifest')->assertDontSee('dist/js/app.js');
    }

    public function test_api_requires_authentication(): void
    {
        $this->getJson('/cabinet-api/data')->assertUnauthorized();
        $this->postJson('/cabinet-api/categories', ['name' => 'Test'])->assertUnauthorized();
    }

    public function test_year_creation_is_atomic_and_creates_twelve_months(): void
    {
        $this->actingAs($this->user())->postJson('/cabinet-api/years', ['year' => 2026])->assertCreated();
        $this->assertDatabaseCount('months', 12);
        $this->postJson('/cabinet-api/years', ['year' => 2026])->assertUnprocessable();
        $this->assertDatabaseCount('years', 1);
        $this->getJson('/cabinet-api/data')->assertOk()->assertJsonPath('years.0.name', '2026');
    }

    public function test_transaction_budget_and_analytics_work_through_json(): void
    {
        $this->actingAs($this->user());
        $month = $this->month();
        $category = Category::create(['name' => 'Продукти']);
        $this->postJson('/cabinet-api/budgets', ['month_id' => $month->id, 'category_id' => $category->id, 'budget' => 1000])->assertOk();
        $payload = ['month_id' => $month->id, 'category_id' => $category->id, 'title' => 'Покупка', 'amount' => 120.50, 'type' => 'cash', 'created_at' => '2026-10-24T12:30'];
        $this->postJson('/cabinet-api/transactions', $payload)->assertOk();
        $this->getJson('/cabinet-api/data?month_id='.$month->id)->assertOk()
            ->assertJsonPath('transactions.total', 1)->assertJsonPath('transactions.items.0.title', 'Покупка')
            ->assertJsonPath('transactions.items.0.type', 'cash')->assertJsonPath('budgets.0.spent', 120.5);
        $this->getJson('/cabinet-api/analytics?year_id='.$month->year_id.'&category_id='.$category->id)->assertOk()->assertJsonPath('amount', 120.5);
        $id = Transaction::first()->id;
        $payload['amount'] = 240.5;
        $this->putJson('/cabinet-api/transactions/'.$id, $payload)->assertOk();
        $this->assertDatabaseHas('transactions', ['id' => $id, 'amount' => 240.5]);
        $this->deleteJson('/cabinet-api/transactions/'.$id)->assertOk();
        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_cash_filter_matches_legacy_cache_values_and_paginates(): void
    {
        $user = $this->user();
        $this->actingAs($user);
        $month = $this->month();
        $category = Category::create(['name' => 'Legacy']);
        for ($i = 0; $i < 22; $i++) Transaction::create(['month_id' => $month->id, 'category_id' => $category->id, 'user_id' => $user->id, 'title' => 'Legacy '.$i, 'amount' => 10, 'type' => 'cache']);
        $this->getJson('/cabinet-api/data?type=cash')->assertOk()->assertJsonPath('transactions.total', 22)->assertJsonCount(20, 'transactions.items')->assertJsonPath('transactions.last_page', 2);
        $this->getJson('/cabinet-api/data?type=cash&page=2')->assertOk()->assertJsonCount(2, 'transactions.items');
        $this->getJson('/cabinet-api/data?type=card')->assertOk()->assertJsonPath('transactions.total', 0);
        // A month returns every matching row, even when an old page query remains.
        $this->getJson('/cabinet-api/data?month_id='.$month->id.'&page=2')->assertOk()
            ->assertJsonCount(22, 'transactions.items')->assertJsonPath('transactions.page', 1)
            ->assertJsonPath('transactions.last_page', 1)->assertJsonPath('transactions.spent', 220);
        $this->getJson('/cabinet-api/data?month_id='.$month->id.'&title=Legacy%2021')->assertOk()
            ->assertJsonCount(1, 'transactions.items')->assertJsonPath('transactions.spent', 10);

    }

    public function test_tasks_support_any_existing_user_without_fixed_ids(): void
    {
        $user = $this->user();
        $this->actingAs($user)->postJson('/cabinet-api/tasks', ['user_id' => $user->id, 'description' => 'Перевірити плани', 'priority' => 'middle', 'task_date' => '2026-10-24', 'task_time' => '14:30'])->assertCreated();
        $task = Task::first();
        $this->patchJson('/cabinet-api/tasks/'.$task->id)->assertOk();
        $this->getJson('/cabinet-api/data?date=2026-10-24')->assertOk()->assertJsonPath('tasks.0.is_completed', true)->assertJsonPath('tasks.0.task_time', '14:30');
        $this->patchJson('/cabinet-api/tasks/'.$task->id)->assertOk();
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'is_completed' => 0]);
        $this->deleteJson('/cabinet-api/tasks/'.$task->id)->assertOk();
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_validation_rejects_invalid_references_and_dates(): void
    {
        $this->actingAs($this->user())->postJson('/cabinet-api/budgets', ['month_id' => 999, 'category_id' => 999, 'budget' => -5])->assertUnprocessable()->assertJsonValidationErrors(['month_id', 'category_id', 'budget']);
        $this->getJson('/cabinet-api/data?date=not-a-date')->assertUnprocessable();
        $this->postJson('/cabinet-api/categories', ['name' => ''])->assertUnprocessable();
    }

    public function test_rates_handle_provider_failure_and_success(): void
    {
        $this->actingAs($this->user());
        Http::fakeSequence()->push(['error' => 'unavailable'], 500)->push(['data' => ['amount' => '1.25']])->push(['data' => ['amount' => '1.25']])->push(['data' => ['amount' => '1.25']]);
        $this->getJson('/cabinet-api/rates')->assertStatus(502)->assertJsonStructure(['message']);
        $this->getJson('/cabinet-api/rates')->assertOk()->assertJsonPath('cny_usdt', 1.25)->assertJsonPath('usdt_uah', 1.25);
    }
    public function test_login_validation_and_logout_are_json_actions(): void
    {
        $user = $this->user();
        $this->postJson('/login', ['email' => $user->email, 'password' => 'wrong'])->assertUnprocessable()->assertJsonValidationErrors(['email']);
        $this->postJson('/login', ['email' => $user->email, 'password' => 'secret123'])->assertOk()->assertJsonStructure(['url']);
        $this->assertAuthenticatedAs($user);
        $this->postJson('/cabinet-api/logout')->assertOk()->assertJsonStructure(['url']);
        $this->assertGuest();
    }

    public function test_password_recovery_and_reset_return_json(): void
    {
        \Illuminate\Support\Facades\Notification::fake();
        $user = $this->user();
        $this->postJson('/forgot-password', ['email' => $user->email])->assertOk()->assertJsonStructure(['message']);
        \Illuminate\Support\Facades\Notification::assertSentTo($user, \Illuminate\Auth\Notifications\ResetPassword::class);
        $token = \Illuminate\Support\Facades\Password::createToken($user);
        $this->postJson('/reset-password', ['email' => $user->email, 'token' => $token, 'password' => 'changed123', 'password_confirmation' => 'changed123'])->assertOk()->assertJsonStructure(['url']);
        $this->assertTrue(Hash::check('changed123', $user->fresh()->password));
    }

    public function test_category_rename_preserves_references_and_validates_input(): void
    {
        $user = $this->user();
        $this->actingAs($user);
        $month = $this->month();
        $category = Category::create(['name' => 'Стара назва']);
        $transaction = Transaction::create(['month_id' => $month->id, 'category_id' => $category->id, 'user_id' => $user->id, 'title' => 'Покупка', 'amount' => 25, 'type' => 'card']);
        $this->putJson('/cabinet-api/categories/'.$category->id, ['name' => 'Нова назва'])->assertOk();
        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Нова назва']);
        $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'category_id' => $category->id]);
        $this->getJson('/cabinet-api/data?month_id='.$month->id)->assertOk()->assertJsonPath('transactions.items.0.category', 'Нова назва');
        $this->putJson('/cabinet-api/categories/'.$category->id, ['name' => ''])->assertUnprocessable()->assertJsonValidationErrors(['name']);
        $this->putJson('/cabinet-api/categories/999999', ['name' => 'Test'])->assertNotFound();
    }

    public function test_only_current_cabinet_routes_remain(): void
    {
        $this->actingAs($this->user());
        foreach (['/year/create', '/categories/create', '/transaction/create', '/tasks/create', '/api/accounts/activate'] as $path) {
            $this->postJson($path, [])->assertNotFound();
        }
        foreach (['/transaction/1/delete', '/tasks/1/delete', '/tasks/1/completed', '/logout', '/testBot', '/setWebhook', '/dashboard-overview-1-page'] as $path) {
            $this->getJson($path)->assertNotFound();
        }
        foreach (['/', '/categories', '/year/1', '/month/1', '/transactions', '/tasks', '/tasks/2026-10-24', '/exchange'] as $path) {
            $this->get($path)->assertOk()->assertSee('finance-app');
        }
    }

    public function test_every_application_route_has_an_existing_controller_method(): void
    {
        foreach (app('router')->getRoutes() as $route) {
            $action = $route->getActionName();
            if (strpos($action, 'App\\Http\\Controllers\\') !== 0) continue;
            [$class, $method] = explode('@', $action);
            $this->assertTrue(class_exists($class), $class);
            $this->assertTrue(method_exists($class, $method), $action);
        }
    }

    public function test_transaction_dates_are_explicit_utc_with_kyiv_edit_values(): void
    {
        config(['app.timezone' => 'UTC', 'finance-telegram.timezone' => 'Europe/Kyiv']);
        $user = $this->user();
        $this->actingAs($user);
        $month = $this->month();
        $category = Category::create(['name' => 'Доставка їжі']);
        $row = Transaction::create(['month_id' => $month->id, 'category_id' => $category->id, 'user_id' => $user->id, 'title' => 'хлибна', 'amount' => 251, 'type' => 'card', 'created_at' => '2026-10-23 21:24:00']);
        $this->getJson('/cabinet-api/data?month_id='.$month->id)->assertOk()
            ->assertJsonPath('transactions.items.0.created_at', '2026-10-23T21:24:00+00:00')
            ->assertJsonPath('transactions.items.0.created_at_local', '2026-10-24T00:24');
        $payload = ['month_id' => $month->id, 'category_id' => $category->id, 'title' => 'хлибна', 'amount' => 251, 'type' => 'card', 'created_at' => '2026-10-24T00:24'];
        $this->putJson('/cabinet-api/transactions/'.$row->id, $payload)->assertOk();
        $this->assertDatabaseHas('transactions', ['id' => $row->id, 'created_at' => '2026-10-23 21:24:00']);
        $payload['title'] = 'Winter';
        $payload['created_at'] = '2026-12-24T00:24';
        $this->postJson('/cabinet-api/transactions', $payload)->assertOk();
        $this->assertDatabaseHas('transactions', ['title' => 'Winter', 'created_at' => '2026-12-23 22:24:00']);
    }

}
