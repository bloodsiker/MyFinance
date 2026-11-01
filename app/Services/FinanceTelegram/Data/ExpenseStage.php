<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram\Data;

enum ExpenseStage: string
{
    case Year = 'year';
    case Month = 'month';
    case Category = 'category';
    case Description = 'description';
    case Amount = 'amount';
    case Payment = 'payment';
    case Confirmation = 'confirm';
}
