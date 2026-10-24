<?php

namespace App\Services\TelegramExchangeBot;

use App\Services\TelegramExchangeBot\Commands\BalanceCommand;
use App\Services\TelegramExchangeBot\Commands\BalanceDepositCommand;
use App\Services\TelegramExchangeBot\Commands\CommandInterface;
use App\Service\TelegramExchangeBot\Commands\FaqCommand;
use App\Service\TelegramExchangeBot\Commands\OrderCreateCommand;
use App\Service\TelegramExchangeBot\Commands\OrderHistoryCommand;
use App\Service\TelegramExchangeBot\Commands\RequestCreateCommand;
use App\Service\TelegramExchangeBot\Commands\RequestEditCommand;
use App\Service\TelegramExchangeBot\Commands\RequestUpdateCommand;
use App\Services\TelegramExchangeBot\Commands\ContactCommand;
use App\Services\TelegramExchangeBot\Commands\CurrencyFromCommand;
use App\Services\TelegramExchangeBot\Commands\CurrencyFromToCommand;
use App\Services\TelegramExchangeBot\Commands\DirectionInformationCommand;
use App\Services\TelegramExchangeBot\Commands\StartCommand;
use App\Services\TelegramExchangeBot\Commands\TransactionCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TelegramBot
{
    const BASE_API_URL = 'https://api.telegram.org/bot';

    protected string $token;

    protected $chatId;

    protected $user;

    protected $command;

    protected $text;

    protected $message;

    protected $messageId;

    protected $lastSendMessageId;

    protected $local;
    protected $telegramContainer;

    public function __construct() {
        $this->token  = '7194044173:AAHBmqQBA4pbn3xgYBAuX4bQDfchIyZn3rA';
        $this->local = 'UA';
    }

    public function run(Request $request)
    {
        $this->prepareData($request);

        try {

//            if ($this->command !== Constants::COMMAND_CONTACT) {
//                $user = $this->getUserInfoFromProcessing();
//
//                if ($user && $user['isBlock']) {
//                    $this->sendMessage('🤷‍ Oooops, Ваш акаунт заблоковано');
//                    return false;
//                }
//            }

//            if (in_array($this->command, [
//                Constants::COMMAND_START,
//                Constants::COMMAND_MENU,
//                Constants::COMMAND_FAQ,
//                Constants::COMMAND_CONTACT,
//                Constants::COMMAND_EXCHANGE,
//                Constants::COMMAND_HISTORY,
//            ], true)) {
//                $this->deleteRedisKey(Constants::NEXT_COMMAND);
//            }

            switch ($this->command) {
                case in_array($this->command, [Constants::COMMAND_START, Constants::COMMAND_MENU], true):

                    (new StartCommand($this))->execute();

                    break;
                case Constants::COMMAND_BALANCE:
                    (new BalanceCommand($this))->execute();

                    break;

                case Constants::COMMAND_TRANSACTION:
                    (new TransactionCommand($this))->execute();

                    break;

                case Constants::COMMAND_DEPOSITS:
                    (new BalanceDepositCommand($this))->execute();

                    break;

                case Constants::COMMAND_BACK_TO_FROM:

                    (new CurrencyFromCommand($this))->setExecuteMethod('editButtons')->execute();

                    break;
                case Constants::COMMAND_EXCHANGE:

                    (new CurrencyFromCommand($this))->execute();

                    break;
                case strpos($this->command, Constants::COMMAND_CURRENCY_FROM):

                    (new CurrencyFromToCommand($this))->execute();

                    break;
                case strpos($this->command, Constants::COMMAND_DIRECTION_INFO):

                    (new DirectionInformationCommand($this))->execute();

                    break;
                case strpos($this->command, Constants::COMMAND_REQUEST_CREATE):

                    (new RequestCreateCommand($this))->execute();

                    break;
                case Constants::COMMAND_REQUEST_UPDATE:

                    (new RequestUpdateCommand($this))->execute();

                    break;
                case strpos($this->command, Constants::COMMAND_REQUEST_EDIT):

                    (new RequestEditCommand($this))->execute();

                    break;
                case strpos($this->command, Constants::COMMAND_ORDER_CREATE):

                    (new OrderCreateCommand($this))->execute();

                    break;
                case Constants::COMMAND_HISTORY:

                    (new OrderHistoryCommand($this))->execute();

                    break;
                case Constants::COMMAND_FAQ:

                    (new FaqCommand($this))->execute();

                    break;
                case Constants::COMMAND_CONTACT:

                    (new ContactCommand($this))->execute();

                    break;
                default:
                    $this->sendMessage('🤷‍ Я вас не розумію');
            }
        } catch (\Exception $exception) {
            $this->log('Error bot: ' . $exception->getMessage());
        }
    }

    /**
     * @return void
     */
    protected function prepareData(Request $request)
    {
        try {
            $data = $request->toArray();

            Log::info('Telegram Webhook:' . print_r($data, 1));

            if (!isset($data['callback_query']) && !isset($data['message'])) {
                $this->log('There is no data in the body of the response from the telegram');
                return;
            }

            $payload = $data['callback_query'] ?? $data['message'];

            $this->user = $payload['from'] ?? null;
            $this->command = $payload['data'] ?? ($payload['text'] ?? null);
            $this->message = $this->command;

            // messageId
            $this->messageId = $payload['message_id'] ?? ($payload['message']['message_id'] ?? null);

            // text
            $this->text = $payload['text']
                ?? ($payload['message']['text'] ?? null);

            // chatId
            $this->chatId = $payload['chat']['id']
                ?? ($payload['message']['chat']['id'] ?? null);

            if (!$this->chatId) {
                $this->log('Cannot detect chat id. Payload: ' . json_encode($payload));
            }

        } catch (\Exception $exception) {
            Log::info('Telegram Webhook Error:'. $exception->getMessage() . ' ' . $exception->getLine());die;
        }
    }

    public function log($content)
    {
        Log::info('Telegram Bot Error: '. $content);die;
    }

    /**
     * @param CommandInterface $command
     *
     * @return $this
     */
    public function executeCommand(CommandInterface $command): TelegramBot
    {
        $command->execute();

        return $this;
    }

    /**
     * @return TelegramBotContainer
     */
    public function getTelegramContainer(): TelegramBotContainer
    {
        return $this->telegramContainer;
    }

    /**
     * @return Processing
     */
    public function getProcessing(): Processing
    {
        return $this->telegramContainer->getProcessing();
    }

    /**
     * @return RedisManager
     */
    public function getRedisManager(): RedisManager
    {
        return $this->telegramContainer->getRedisManager();
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->telegramContainer->getContainer();
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->telegramContainer->getTranslator();
    }

    /**
     * @return mixed
     */
    public function getChatId()
    {
        return $this->chatId;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @return mixed
     */
    public function getLastSendMessageId()
    {
        return $this->lastSendMessageId;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getLocal()
    {
        return $this->local;
    }

    public function sendMessage($message): TelegramBot
    {
        $this->sendPost('sendMessage', [
            'text' => (string) $message,
            'chat_id' => $this->chatId,
            'disable_web_page_preview' => true,
            'parse_mode' => 'html',
        ]);

        return $this;
    }

    public function editMessage($message, $messageId, $chatId = null): TelegramBot
    {
        $this->sendPost('editMessageText', [
            'text' => (string) $message,
            'chat_id' => $chatId ?: $this->chatId,
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
            'message_id' => $messageId
        ]);

        return $this;
    }

    public function sendButtons($message, $button): TelegramBot
    {
        $this->sendPost('sendMessage', [
            'text' => (string) $message,
            'chat_id' => $this->chatId,
            'parse_mode' => 'html',
            'reply_markup' => json_encode($button),
        ]);

        return $this;
    }

    public function editButtons($message, $button, $messageId): TelegramBot
    {
        $this->sendPost( 'editMessageText', [
            'text' => (string) $message,
            'chat_id' => $this->chatId,
            'parse_mode' => 'html',
            'reply_markup' => json_encode($button),
            'message_id' => $messageId
        ]);

        return $this;
    }

    public function removeKeyboard($message = null): TelegramBot
    {
        $this->sendPost('sendMessage', [
            'text' => (string) $message,
            'chat_id' => $this->chatId,
            'parse_mode' => 'html',
            'reply_markup' => json_encode(['remove_keyboard' => true,]),
        ]);

        return $this;
    }

    /**
     * @param  string  $methodName
     * @param  array  $data
     *
     * @return mixed|null
     */
    private function sendPost(string $methodName, array $data = [])
    {
        if (is_array($data)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->buildUrl($methodName));
            curl_setopt($ch, CURLOPT_POST, count($data));
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
        }

        if ($result) {
            $result = json_decode($result, true);

            if (isset($result['result']['message_id'])) {
                $this->lastSendMessageId = $result['result']['message_id'];
            }

            return $result;
        }

        return null;
    }

    /**
     * @param  string  $url
     * @param  array  $data
     *
     * @return mixed|null
     */
    public function curlRequest(string $url, array $data = [])
    {
        if (is_array($data)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($data));
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded', 'X-Requested-With: XMLHttpRequest']);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);

            $error = curl_error($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($code != 200) {
                $this->log(sprintf('Error curl: %s, Code: %s', $error, $code) );
            }

            curl_close($ch);
        }

        $this->log('Curl result: ' . $result);

        if ($result) {
            return json_decode($result, true);
        }

        return null;
    }

    /**
     * @param string $methodName
     *
     * @return string
     */
    private function buildUrl(string $methodName): string
    {
        return self::BASE_API_URL . $this->token . '/' . $methodName;
    }
}
