<?php

namespace App\Services\TelegramExchangeBot;

class Constants
{
    const CONFIGURATION = 'configuration';

    const COMMAND_START    = '/start';
    const COMMAND_MENU     = '/menu';
    const COMMAND_BALANCE  = 'Баланс';
    const COMMAND_TRANSACTION  = 'Транзакції';
    const COMMAND_DEPOSITS  = 'Банкі';
    const COMMAND_FAQ      = 'Допомога';
    const COMMAND_CONTACT  = 'Контакти';
    const COMMAND_EXCHANGE = 'Обмін';
    const COMMAND_HISTORY  = 'Історія обмінів';
    const COMMAND_REQUEST_UPDATE  = 'request_update';
    const COMMAND_REQUEST_CREATE  = 'request_create';
    const COMMAND_REQUEST_EDIT    = 'request_edit';
    const COMMAND_ORDER_CREATE    = 'order_create';
    const COMMAND_DIRECTION_INFO  = 'direction_information';
    const COMMAND_CURRENCY_FROM   = 'currency_from';
    const COMMAND_BACK_TO_FROM    = 'back_to_from';

    const LAST_MESSAGE_DIRECTION_INFO_ID = 'direction_info_message_id';
    const LAST_MESSAGE_REQUEST_UPDATE_ID = 'request_update_message_id';

    const TTL_1D = 86400;
}
