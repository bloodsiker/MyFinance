<?php

return [
    'token' => env('FINANCE_TELEGRAM_BOT_TOKEN'),
    'cabinet_url' => env('FINANCE_TELEGRAM_CABINET_URL'),
    'timezone' => env('FINANCE_TELEGRAM_TIMEZONE', 'Europe/Kyiv'),
    'username' => env('FINANCE_TELEGRAM_BOT_USERNAME'),
    'webhook_secret' => env('FINANCE_TELEGRAM_WEBHOOK_SECRET'),
    // Explicit mapping: Telegram user ID => existing cabinet user ID.
    'users' => json_decode(env('FINANCE_TELEGRAM_USERS', '{}'), true) ?: [],
];
