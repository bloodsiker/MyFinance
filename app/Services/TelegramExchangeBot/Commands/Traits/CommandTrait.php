<?php

namespace App\Services\TelegramExchangeBot\Commands\Traits;

use App\Services\TelegramExchangeBot\Constants;

trait CommandTrait
{
    /**
     * @return void
     */
    public function sendError($message = '🚫 Виникла помилка')
    {
        $buttonsInline = [
            'inline_keyboard' => [
                [
                    ['text' => '📚 Головне меню', 'callback_data' => Constants::COMMAND_MENU],
                ]
            ]
        ];

        $this->telegramBot->sendButtons($message, $buttonsInline);
    }

    /**
     * @param $output
     *
     * @return void
     */
    public function sendMessage($output)
    {
        $this->telegramBot->sendMessage($output);
    }

    public function editMessage($message, $messageId)
    {
        $this->telegramBot->editMessage($message, $messageId);
    }

    public function sendButtons($message, $buttons)
    {
        $this->telegramBot->sendButtons($message, $buttons);
    }

    public function editButtons($message, $buttons, $messageId = null)
    {
        $this->telegramBot->editButtons($message, $buttons, $messageId ?: $this->telegramBot->getMessageId());
    }
}
