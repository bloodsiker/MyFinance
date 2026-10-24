<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram;

use App\Models\Month;

final class TextFormatter
{
    public function money(float|int|string $amount): string
    {
        return number_format((float) $amount, 2, ',', ' ');
    }

    public function monthLabel(Month $month): string
    {
        return $month->name.' '.optional($month->year)->name;
    }
}
