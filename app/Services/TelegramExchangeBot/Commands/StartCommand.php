<?php

namespace App\Services\TelegramExchangeBot\Commands;

use App\Services\TelegramExchangeBot\Constants;

class StartCommand extends AbstractCommand implements CommandInterface
{
    /**
     * @return void
     */
    public function execute(): void
    {
        $buttons = [
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'keyboard' => [
                [
                    ['text' => Constants::COMMAND_BALANCE],
                    ['text' => Constants::COMMAND_TRANSACTION],
                ],
                [
                    ['text' => Constants::COMMAND_DEPOSITS],
                ]
            ]
        ];

        $this->sendButtons('Головне меню', $buttons);
    }
}
