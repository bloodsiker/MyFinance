<?php

namespace App\Services\FinanceTelegram;

use App\Models\User;
use App\Services\FinanceTelegram\Contracts\AccountAccess;
use Illuminate\Support\Facades\{Cache, DB, Http};
use Illuminate\Validation\ValidationException;

class AccountLink implements AccountAccess
{
    public function resolve(string $telegramId): ?User
    {
        $bound = User::where('telegram_id', $telegramId)->first();
        if ($bound) return $bound;
        $legacyId = config('finance-telegram.users', [])[$telegramId] ?? null;
        // Once managed in the cabinet, an account must never fall back to stale .env permissions.
        return $legacyId ? User::whereKey($legacyId)->whereNull('telegram_id')->whereNull('telegram_link_managed_at')->first() : null;
    }

    public function displayId(User $user): ?string
    {
        if ($user->telegram_id) return $user->telegram_id;
        if ($user->telegram_link_managed_at) return null;
        foreach (config('finance-telegram.users', []) as $telegramId => $userId) {
            if ((string) $userId === (string) $user->id) return (string) $telegramId;
        }
        return null;
    }

    public function issue(User $user): array
    {
        $token = config('finance-telegram.token');
        if (!$token) throw ValidationException::withMessages(['telegram' => 'Токен Telegram-бота ще не налаштований.']);
        $username = config('finance-telegram.username');
        if (!$username) {
            try {
                $username = Cache::remember('finance-bot-username:'.hash('sha256', $token), 3600, function () use ($token) {
                    $response = Http::timeout(8)->get('https://api.telegram.org/bot'.$token.'/getMe');
                    if (!$response->successful() || !$response->json('ok') || !$response->json('result.username')) throw new \RuntimeException();
                    return $response->json('result.username');
                });
            } catch (\Throwable $exception) {
                throw ValidationException::withMessages(['telegram' => 'Не вдалося отримати адресу бота. Перевірте токен і спробуйте ще раз.']);
            }
        }
        $username = ltrim($username, '@');
        if (!preg_match('/^[A-Za-z0-9_]{5,32}$/', $username)) throw ValidationException::withMessages(['telegram' => 'Некоректне ім’я Telegram-бота.']);
        $code = bin2hex(random_bytes(24));
        $expires = now()->addMinutes(10);
        DB::transaction(function () use ($user, $code, $expires) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            DB::table('finance_telegram_links')->where('user_id', $user->id)->whereNull('used_at')->delete();
            DB::table('finance_telegram_links')->insert(['token_hash' => hash('sha256', $code), 'user_id' => $user->id, 'expires_at' => $expires]);
        });
        return ['url' => 'https://t.me/'.$username.'?start=link_'.$code, 'expires_at' => $expires->toIso8601String()];
    }

    public function consume(string $code, string $telegramId): array
    {
        return DB::transaction(function () use ($code, $telegramId) {
            $link = DB::table('finance_telegram_links')->where('token_hash', hash('sha256', $code))->lockForUpdate()->first();
            if (!$link) return ['ok' => false, 'message' => 'Посилання недійсне. Відкрийте бота кнопкою в кабінеті.'];
            $user = User::whereKey($link->user_id)->lockForUpdate()->first();
            if (!$user) return ['ok' => false, 'message' => 'Користувача більше немає.'];
            if ($link->used_at) {
                if ($link->claimed_by === $telegramId && $user->telegram_id === $telegramId) return ['ok' => true, 'user' => $user];
                return ['ok' => false, 'message' => 'Це посилання вже використане. Створіть нове в кабінеті.'];
            }
            if (now()->greaterThan($link->expires_at)) return ['ok' => false, 'message' => 'Час дії посилання минув. Натисніть кнопку в кабінеті ще раз.'];
            $owner = $this->resolve($telegramId);
            if ($owner && $owner->id !== $user->id) return ['ok' => false, 'message' => 'Цей Telegram вже прив’язаний до іншого користувача. Спочатку відв’яжіть його в кабінеті.'];
            $user->forceFill(['telegram_id' => $telegramId, 'telegram_link_managed_at' => now()])->save();
            DB::table('finance_telegram_chats')->where('user_id', $user->id)->orWhere('chat_id', $telegramId)->delete();
            DB::table('finance_telegram_links')->where('token_hash', $link->token_hash)->update(['used_at' => now(), 'claimed_by' => $telegramId]);
            return ['ok' => true, 'user' => $user];
        }, 3);
    }
}
