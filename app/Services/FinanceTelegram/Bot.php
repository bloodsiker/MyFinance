<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram;

use App\Services\FinanceTelegram\Contracts\AccountAccess;
use App\Services\FinanceTelegram\Contracts\TelegramTransport;
use App\Services\FinanceTelegram\Contracts\UpdateProcessor;
use App\Services\FinanceTelegram\Data\Reply;
use App\Services\FinanceTelegram\Data\TelegramUpdate;

final class Bot
{
    public function __construct(
        private readonly AccountAccess $accounts,
        private readonly UpdateProcessor $processor,
        private readonly TelegramTransport $transport,
    ) {
    }

    public function handle(array $payload): void
    {
        $update = TelegramUpdate::fromArray($payload);
        if ($update === null) {
            return;
        }

        $linkedNow = false;
        if (($code = $update->linkCode()) !== null) {
            $result = $this->accounts->consume($code, $update->senderId);
            if (!$result['ok']) {
                $this->deliver($update, Reply::text($result['message']));
                return;
            }
            $linkedNow = true;
            $update = $update->withText('/start');
        }

        $user = $this->accounts->resolve($update->senderId);
        if ($user === null) {
            $this->deliver($update, Reply::text(
                'Доступ ще не налаштований. Ваш Telegram ID: '.$update->senderId
                .'. Відкрийте розділ «Користувачі» в кабінеті та натисніть «Відкрити Telegram-бота» або введіть цей ID вручну.'
            ));
            return;
        }

        $this->deliver($update, $this->processor->process($update, $user, $linkedNow));
    }

    private function deliver(TelegramUpdate $update, Reply $reply): void
    {
        $this->transport->send($update->chatId, $reply);
        $this->transport->acknowledge($update);
    }
}
