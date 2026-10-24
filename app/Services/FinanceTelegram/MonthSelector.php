<?php

declare(strict_types=1);

namespace App\Services\FinanceTelegram;

use App\Models\Month;
use App\Models\Year;
use App\Services\FinanceTelegram\Data\Conversation;

final class MonthSelector
{
    public function selected(Conversation $conversation): ?Month
    {
        return ($conversation->monthId() ? Month::find($conversation->monthId()) : null) ?? $this->current();
    }

    private function current(): ?Month
    {
        $year = Year::where('name', now()->year)->first() ?? Year::orderByDesc('name')->first();
        if (!$year) {
            return null;
        }

        return Month::where('year_id', $year->id)->where('number', (string) now()->month)->first()
            ?? Month::where('year_id', $year->id)->orderByRaw('CAST(number AS UNSIGNED)')->first();
    }
}
