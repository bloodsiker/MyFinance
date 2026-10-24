<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Persistence;

use App\Models\User;
use App\Services\FinanceTelegram\CommandRouter;
use App\Services\FinanceTelegram\Contracts\UpdateProcessor;
use App\Services\FinanceTelegram\Data\{Conversation, Reply, TelegramUpdate};
use Illuminate\Support\Facades\DB;

final class DatabaseUpdateProcessor implements UpdateProcessor
{
    public function __construct(private readonly CommandRouter $router)
    {
    }

    public function process(TelegramUpdate $update, User $user, bool $linkedNow): Reply
    {
        // State, the expense and its persisted reply commit together. Delivery is
        // deliberately outside this transaction, so a retry only replays the reply.
        return DB::transaction(function () use ($update, $user, $linkedNow): Reply {
            $freshUser = User::whereKey($user->id)->lockForUpdate()->first();
            if (!$freshUser || (($freshUser->telegram_id || $freshUser->telegram_link_managed_at)
                && (string) $freshUser->telegram_id !== $update->chatId)) {
                return Reply::text('Прив’язку Telegram змінено. Відкрийте бота заново через кабінет.');
            }

            DB::table('finance_telegram_chats')->insertOrIgnore([
                'chat_id' => $update->chatId,
                'user_id' => $user->id,
                'state' => '{}',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $chat = DB::table('finance_telegram_chats')->where('chat_id', $update->chatId)->lockForUpdate()->first();
            $processed = DB::table('finance_telegram_updates')->where('update_id', $update->id)->first();
            if ($processed) {
                return Reply::fromArray(json_decode($processed->reply, true, 512, JSON_THROW_ON_ERROR));
            }

            $state = $chat->user_id == $user->id ? json_decode($chat->state, true, 512, JSON_THROW_ON_ERROR) : [];
            $conversation = new Conversation($state);
            $reply = $this->router->dispatch($update, $freshUser, $conversation);
            if ($linkedNow) {
                $reply = $reply->withPrefix('Telegram успішно прив’язано до акаунта '.($freshUser->name ?: $freshUser->email).".\n\n");
            }

            DB::table('finance_telegram_chats')->where('chat_id', $update->chatId)->update([
                'user_id' => $user->id,
                'state' => json_encode($conversation->toArray(), JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'updated_at' => now(),
            ]);
            DB::table('finance_telegram_updates')->insert([
                'update_id' => $update->id,
                'chat_id' => $update->chatId,
                'reply' => json_encode($reply->toArray(), JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'created_at' => now(),
            ]);

            return $reply;
        }, 3);
    }
}
