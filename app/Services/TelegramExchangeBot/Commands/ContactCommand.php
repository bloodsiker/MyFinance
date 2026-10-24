<?php

namespace App\Services\TelegramExchangeBot\Commands;

class ContactCommand extends AbstractCommand implements CommandInterface
{
    /**
     * @return void
     */
    public function execute(): void
    {
        $output = '<b>📕 Наші контакти</b>' . PHP_EOL . PHP_EOL;
        $output .= "<b>Telegram: </b> @Letspayme" . PHP_EOL;
        $output .= "<b>E-mail: </b> info@letspay.me" . PHP_EOL;
        $output .= "<b>Telegram channel: </b> @Lets_pay" . PHP_EOL;

        $this->sendMessage($output);
    }
}
