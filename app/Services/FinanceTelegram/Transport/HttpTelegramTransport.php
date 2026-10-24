<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Transport;

use App\Services\FinanceTelegram\Contracts\TelegramTransport;
use App\Services\FinanceTelegram\Data\{Reply, TelegramUpdate};
use Illuminate\Support\Facades\Http;
use RuntimeException;

final class HttpTelegramTransport implements TelegramTransport
{
    private const MESSAGE_LIMIT = 3800;

    public function send(string $chatId, Reply $reply): void
    {
        $payload = $reply->toArray();
        if (!empty($payload['edit_message_id']) && mb_strlen($payload['text']) <= self::MESSAGE_LIMIT
            && $this->edit($chatId, $payload)) {
            return;
        }

        $chunks = mb_str_split($payload['text'], self::MESSAGE_LIMIT);
        foreach ($chunks as $index => $text) {
            $message = ['chat_id' => (int) $chatId, 'text' => $text];
            if ($index === count($chunks) - 1 && ($markup = $this->replyMarkup($payload))) {
                $message['reply_markup'] = $markup;
            }
            $response = Http::timeout(10)->post($this->endpoint('sendMessage'), $message);
            if (!$response->successful() || !$response->json('ok')) {
                throw new RuntimeException('Telegram delivery failed');
            }
        }
    }

    public function acknowledge(TelegramUpdate $update): void
    {
        if ($update->callbackQueryId !== null) {
            Http::timeout(5)->post($this->endpoint('answerCallbackQuery'), ['callback_query_id' => $update->callbackQueryId]);
        }
    }

    private function edit(string $chatId, array $reply): bool
    {
        $response = Http::timeout(10)->post($this->endpoint('editMessageText'), [
            'chat_id' => (int) $chatId,
            'message_id' => $reply['edit_message_id'],
            'text' => $reply['text'],
            'reply_markup' => ['inline_keyboard' => $reply['buttons'] ?? []],
        ]);

        return ($response->successful() && $response->json('ok'))
            || str_contains((string) $response->json('description'), 'message is not modified');
    }

    private function replyMarkup(array $reply): ?array
    {
        if (!empty($reply['keyboard'])) {
            return [
                'keyboard' => $reply['keyboard'],
                'resize_keyboard' => true,
                'is_persistent' => true,
                'one_time_keyboard' => false,
                'input_field_placeholder' => 'Оберіть дію або введіть повідомлення',
            ];
        }

        return !empty($reply['buttons']) ? ['inline_keyboard' => $reply['buttons']] : null;
    }

    private function endpoint(string $method): string
    {
        return 'https://api.telegram.org/bot'.config('finance-telegram.token').'/'.$method;
    }
}
