<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabinetUsersController;
use App\Http\Controllers\FinanceCabinetController;
use App\Http\Controllers\FinanceTelegramController;
use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;

Route::post('/telegram/webhook', [FinanceTelegramController::class, 'webhook'])->name('finance-telegram.webhook');

// The existing Telegram integration remains independent of the Vue cabinet.
Route::post('/webhook', [TelegramController::class, 'webhook'])->name('telegram.webhook');

// Authentication screens are rendered by the same standalone Vue application.
Route::get('/login', [FinanceCabinetController::class, 'index'])->name('login-view');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/forgot-password', [FinanceCabinetController::class, 'index'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'forgotPasswordSend'])->name('forgot-password.send');
Route::get('/reset-password/{token}', [FinanceCabinetController::class, 'index'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');

Route::middleware('auth')->group(function () {
    // Direct URLs return the shell; navigation inside the cabinet is handled by Vue.
    Route::get('/', [FinanceCabinetController::class, 'index'])->name('dashboard');
    Route::get('/users', [FinanceCabinetController::class, 'index'])->name('users');
    Route::get('/categories', [FinanceCabinetController::class, 'index'])->name('category.list');
    Route::get('/year/{id}', [FinanceCabinetController::class, 'index'])->whereNumber('id')->name('month.list');
    Route::get('/month/{id}', [FinanceCabinetController::class, 'index'])->whereNumber('id')->name('transactions.list');
    Route::get('/transactions', [FinanceCabinetController::class, 'index'])->name('transactions.all');
    Route::get('/tasks', [FinanceCabinetController::class, 'index'])->name('task.list');
    Route::get('/tasks/{date}', [FinanceCabinetController::class, 'index'])->where('date', '\d{4}-\d{2}-\d{2}')->name('task.list_by_date');
    Route::get('/exchange', [FinanceCabinetController::class, 'index'])->name('exchange');

    Route::prefix('cabinet-api')->group(function () {
        Route::get('/data', [FinanceCabinetController::class, 'data']);
        Route::get('/users', [CabinetUsersController::class, 'index']);
        Route::put('/users/{id}/telegram', [CabinetUsersController::class, 'telegram'])->whereNumber('id');
        Route::patch('/users/{id}/telegram-notifications', [CabinetUsersController::class, 'telegramNotifications'])->whereNumber('id');
        Route::post('/users/{id}/telegram-link', [CabinetUsersController::class, 'link'])->whereNumber('id');
        Route::post('/years', [FinanceCabinetController::class, 'year']);
        Route::post('/categories', [FinanceCabinetController::class, 'category']);
        Route::put('/categories/{id}', [FinanceCabinetController::class, 'category'])->whereNumber('id');
        Route::post('/budgets', [FinanceCabinetController::class, 'budget']);
        Route::get('/analytics', [FinanceCabinetController::class, 'analytics']);
        Route::post('/transactions', [FinanceCabinetController::class, 'saveTransaction']);
        Route::put('/transactions/{id}', [FinanceCabinetController::class, 'saveTransaction'])->whereNumber('id');
        Route::delete('/transactions/{id}', [FinanceCabinetController::class, 'deleteTransaction'])->whereNumber('id');
        Route::post('/tasks', [FinanceCabinetController::class, 'task']);
        Route::patch('/tasks/{id}', [FinanceCabinetController::class, 'toggleTask'])->whereNumber('id');
        Route::delete('/tasks/{id}', [FinanceCabinetController::class, 'deleteTask'])->whereNumber('id');
        Route::get('/rates', [FinanceCabinetController::class, 'rates']);
        Route::post('/logout', [FinanceCabinetController::class, 'logout']);
    });
});
