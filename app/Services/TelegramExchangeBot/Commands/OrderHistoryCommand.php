<?php

namespace App\Service\TelegramExchangeBot\Commands;

use App\Entity\TelegramRequest;
use App\Service\TelegramExchangeBot\Constants;

class OrderHistoryCommand extends AbstractCommand implements CommandInterface
{
    /**
     * @var string $description
     */
    protected $description = 'Історія обмінів';

    /**
     * @return false|void
     */
    public function execute()
    {
        $processing = $this->telegramBot->getProcessing();

        $requests = $processing->getUserRequests($this->telegramBot->getUser()->id);

        if ($requests) {
            $htmlSuccess = '📥 <b>Ваші заявки</b>' . PHP_EOL;
            foreach ($requests as $request) {
                if ($request['removed'] == 1 || $request['isPayedBack'] == -1) {
                    $status = $this->telegramBot->getTranslator()->trans('table.rejected');
                    $statusIcon = '🔴';
                } elseif ($request['isApproved'] == 1) {
                    $status = $this->telegramBot->getTranslator()->trans('table.finished');
                    $statusIcon = '🟢';
                } else {
                    $status = $this->telegramBot->getTranslator()->trans('table.in_process');
                    $statusIcon = '⚪️';
                }
                $statusUrl = sprintf('%s/%s/order/status/%s', $this->telegramBot->getFrontHost(), $request['lang'], $request['secret']);
                $htmlSuccess .= PHP_EOL . sprintf('%s <a href="%s">Заявка №%s</a>', $statusIcon, $statusUrl, $request['id']);
                $htmlSuccess .=  sprintf(' - %s %s ➡️ %s %s', $request['sumFrom'], $request['currencyFrom']['name'], $request['sumTo'], $request['currencyTo']['name']);
                $htmlSuccess .=  sprintf(' - (%s)', $status);
            }
            $this->sendMessage($htmlSuccess);
        } else {
            $this->sendMessage('🤷‍ Ви ще не зробили жодного обміну');
        }
    }
}