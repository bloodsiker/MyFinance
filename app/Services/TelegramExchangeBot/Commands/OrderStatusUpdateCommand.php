<?php

namespace App\Service\TelegramExchangeBot\Commands;

use App\Entity\TelegramRequest;
use App\Service\TelegramExchangeBot\Constants;

class OrderStatusUpdateCommand extends AbstractCommand implements CommandInterface
{
    /**
     * @var string $description
     */
    protected $description = 'Оновлення статусу по заявці';

    /**
     * @var TelegramRequest $telegramRequest
     */
    protected $telegramRequest = null;

    /**
     * @return void
     */
    public function execute()
    {
        $processing = $this->telegramBot->getProcessing();

        $result = $processing->getOrderStatusByById($this->telegramRequest->getChangeRequestId());

        $this->telegramBot->log('Update request status: ' . json_encode($result));

        if ($result) {

            $data = $result['data'];
            $space = '〰️〰️〰️〰️〰️〰️';

            $statusMessage = '⚙️ Статус заявки';

            if ($data['is_removed'] == 1 || $data['is_payed_back'] == -1) {
                $statusMessage .= PHP_EOL . PHP_EOL . '🔴 Заявка відмінена';
            } elseif ($data['is_payed_back'] == -2) {
                $statusMessage .= PHP_EOL . PHP_EOL . '🟠 Не повна виплата';
            } elseif ($data['is_payed_back'] == 10) {
                $statusMessage .= PHP_EOL . PHP_EOL . '⚪️ В очікуванні';
            } else {
                $statusMessage .= PHP_EOL . PHP_EOL . 'Очікуємо оплату по заявці';
            }

            if ($data['address']) {
                $statusMessage .= PHP_EOL . $space . '⬇️' . $space;
                $statusMessage .= PHP_EOL . sprintf('Ви відправили криптовалюту %s', $data['address_balance']);
                if ($data['commission']) {
                    $statusMessage .= PHP_EOL . sprintf('Комісія за відправку криптовалюти %s', $data['commission']);
                }
                if ($data['address_confirmations'] && $data['address_required_confirmations']) {
                    $statusMessage .= PHP_EOL . sprintf('Конфірми %s/%s', $data['address_confirmations'], $data['address_required_confirmations']);
                } else {
                    $statusMessage .= PHP_EOL . 'Конфірми невідомо';
                }
            }

            if ($data['is_payed'] == 1) {
                $statusMessage .= PHP_EOL . $space . '⬇️' . $space;
                $statusMessage .= PHP_EOL . sprintf('Заявка оплачена %s %s', $data['sum_from'], $data['currency_from']);
            }

            if ($data['prev_sum_from'] && $data['prev_sum_to']) {
                $statusMessage .= PHP_EOL . $space . '⬇️' . $space;
                $statusMessage .= PHP_EOL . 'Сума заявки пересчитана';
                $statusMessage .= PHP_EOL . sprintf('Було: %s %s - %s %s', $data['prev_sum_from'], $data['currency_from'], $data['prev_sum_to'], $data['currency_to']);
                $statusMessage .= PHP_EOL . sprintf('Стало: %s %s - %s %s', $data['sum_from'], $data['currency_from'], $data['sum_to'], $data['currency_to']);
            }

            if ($data['is_payed_back'] == 1) {
                $statusMessage .= PHP_EOL . $space . '⬇️' . $space;
                $statusMessage .= PHP_EOL . sprintf('🟢 Кошти відправленно клієнту %s %s', $data['sum_to'], $data['currency_to']);
            }

            if ($data['is_approved'] == 1) {
                $statusMessage .= PHP_EOL . $space . '⬇️' . $space;
                $statusMessage .= PHP_EOL . '🟢 Заявка завершена';
            }


            $this->telegramBot->editMessage($statusMessage, $this->telegramRequest->getMessageId(), $this->telegramRequest->getChatId());
        }
    }

    /**
     * @param TelegramRequest $telegramRequest
     *
     * @return $this
     */
    public function setTelegramRequest(TelegramRequest $telegramRequest)
    {
        $this->telegramRequest = $telegramRequest;

        return $this;
    }
}