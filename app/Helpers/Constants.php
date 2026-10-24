<?php

namespace App\Helpers;


class Constants
{
    const BITRIX_TRAINING_FORMAT_FREE = '7700';
    const BITRIX_TRAINING_FORMAT_CHILD = '7702';

    const TRAINING_FORMAT_FREE = 'free_listener';
    const TRAINING_FORMAT_CHILD = 'child';

    public static $trainingFormats = [
        self::TRAINING_FORMAT_FREE => 'Вільні слухачі',
        self::TRAINING_FORMAT_CHILD => 'Учні',
    ];

    const IPAY_ID_TOV = 10740; // ТОВ ЦЕНТР ОСВІТИ ДЖЕММ
    const IPAY_ID_FOP = 11503; // ФОП Василевський Олег Володимирович

    public static $ipayIds = [
        self::TRAINING_FORMAT_CHILD => self::IPAY_ID_TOV,
        self::TRAINING_FORMAT_FREE => self::IPAY_ID_FOP,
    ];

    const TARIFF_TEST = 'test';
    const TARIFF_LEARNER = 'learner';
    const TARIFF_LEARNER_2 = 'learner2';
    const TARIFF_LISTENER = 'listener';
    const TARIFF_LISTENER_2 = 'listener2';

    public static $choiceTariffs = [
        self::TARIFF_TEST => "«Учень»",
        self::TARIFF_LEARNER_2 => "«Джеммер»",
        self::TARIFF_LISTENER_2 => "«Супер Джеммер с супервайзингом»",
    ];
}
