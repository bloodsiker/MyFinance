<?php

namespace App\Console\Commands;

use App\Services\FinanceTelegram\Bot;
use App\Services\FinanceTelegram\CommandMenu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FinanceTelegramCommand extends Command
{
    protected $signature = 'finance:telegram {mode : poll, webhook or menu} {--url= : Public HTTPS webhook URL} {--once : Run one polling request}';
    protected $description = 'Run the Finance bot locally or register its HTTPS webhook';

    public function handle(Bot $bot, CommandMenu $menu)
    {
        $token = config('finance-telegram.token');
        if (!$token) {
            $this->error('Set FINANCE_TELEGRAM_BOT_TOKEN in .env, then run php artisan config:clear.');
            return 1;
        }
        if (!in_array($this->argument('mode'), ['poll', 'webhook', 'menu'], true)) {
            $this->error('Mode must be poll, webhook or menu.');
            return 1;
        }
        // Keep exactly one local poller per token. OS locks are released when
        // the process exits, including Ctrl+C, without an expiring cache lease.
        $pollLock = null;
        if ($this->argument('mode') === 'poll') {
            $directory = storage_path('framework/cache');
            if (!is_dir($directory)) mkdir($directory, 0755, true);
            $pollLock = fopen($directory.'/finance-telegram-poll-'.hash('sha256', $token).'.lock', 'c');
            if (!$pollLock || !flock($pollLock, LOCK_EX | LOCK_NB)) {
                $this->error('Finance polling is already running. Do not start a second process.');
                return 1;
            }
        }
        $base = 'https://api.telegram.org/bot'.$token.'/';
        try {
            $menu->register();
        } catch (\Throwable $exception) {
            $this->warn('Could not update the Telegram command menu. Check token and Telegram availability.');
            if ($this->argument('mode') === 'menu') return 1;
        }
        if ($this->argument('mode') === 'menu') {
            $this->info('Finance Telegram menu updated.');
            return 0;
        }

        if ($this->argument('mode') === 'webhook') {
            $url = $this->option('url');
            $secret = config('finance-telegram.webhook_secret');
            if (!$secret || !preg_match('/^[a-zA-Z0-9_-]{1,256}$/', $secret) || !filter_var($url, FILTER_VALIDATE_URL) || parse_url($url, PHP_URL_SCHEME) !== 'https') {
                $this->error('Provide a public HTTPS --url and a 1–256 character FINANCE_TELEGRAM_WEBHOOK_SECRET (letters, digits, _ or -).');
                return 1;
            }
            try {
                $result = Http::timeout(15)->post($base.'setWebhook', ['url' => $url, 'secret_token' => $secret, 'allowed_updates' => ['message', 'callback_query']]);
                if (!$result->successful() || !$result->json('ok')) throw new \RuntimeException();
            } catch (\Throwable $exception) {
                $this->error('Telegram did not accept the webhook. Check the token, HTTPS URL and server availability.');
                return 1;
            }
            $this->info('Finance webhook registered.');
            return 0;
        }
        try {
            $info = Http::timeout(10)->get($base.'getWebhookInfo');
            if (!$info->successful() || !$info->json('ok')) throw new \RuntimeException();
            if ($info->json('result.url')) {
                $this->error('This bot already has a webhook. Use its webhook deployment; polling cannot run simultaneously.');
                return 1;
            }
            $offset = 0;
            $this->info('Finance bot is running. Stop with Ctrl+C.');
            do {
                $result = Http::timeout(35)->get($base.'getUpdates', ['offset' => $offset, 'timeout' => $this->option('once') ? 0 : 25, 'allowed_updates' => json_encode(['message', 'callback_query'])]);
                if (!$result->successful() || !$result->json('ok')) throw new \RuntimeException();
                foreach ($result->json('result', []) as $update) {
                    $bot->handle($update);
                    $offset = $update['update_id'] + 1;
                }
            } while (!$this->option('once'));
        } catch (\Throwable $exception) {
            $this->error('Finance bot stopped. Check configuration, migrations and Telegram availability, then restart. No bot token was printed.');
            return 1;
        }
        return 0;
    }
}
