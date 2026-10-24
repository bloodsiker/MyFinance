<?php
namespace App\Services\TelegramExchangeBot\Commands;

use App\Services\TelegramExchangeBot\Commands\Traits\CommandTrait;
use App\Services\TelegramExchangeBot\Constants;
use App\Services\TelegramExchangeBot\TelegramBot;

abstract class AbstractCommand
{
    use CommandTrait;

    /**
     * Name
     *
     * @var string
     */
    public static $name = '';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var TelegramBot
     */
    protected $telegramBot;

    /**
     * @param  TelegramBot  $telegramBot
     */
    public function __construct(TelegramBot $telegramBot)
    {
        $this->telegramBot = $telegramBot;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return static::$name;
    }

    /**
     * @return array|null
     */
    public function getRefreshConfig(): ?array
    {
        $redis = $this->telegramBot->getRedisManager();
        $result = json_decode($redis->get(Constants::CONFIGURATION), true);

        $directions = [];

        foreach ($result['directions'] as $direction) {
            if ($direction['is_telegram_show']) {
                $directions[$direction['currencyFrom']][$direction['currencyTo']] = $direction;
            }
        }

        $result['directions'] = $directions;

        return $result;
    }
}
