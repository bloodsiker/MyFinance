<?php

namespace App\Http\Controllers;

use App\Services\FinanceTelegram\Bot;
use Illuminate\Http\Request;

class FinanceTelegramController extends Controller
{
    public function webhook(Request $request, Bot $bot)
    {
        $secret = (string) config('finance-telegram.webhook_secret');
        abort_unless($secret && config('finance-telegram.token'), 503, 'Finance bot is not configured.');
        abort_unless(hash_equals($secret, (string) $request->header('X-Telegram-Bot-Api-Secret-Token')), 403);
        $data = $request->validate(['update_id' => 'required|integer|min:0', 'message' => 'sometimes|array', 'callback_query' => 'sometimes|array']);
        try {
            $bot->handle($data);
        } catch (\Throwable $exception) {
            // Do not expose request URLs containing the bot token or chat contents.
            report(new \RuntimeException('Finance Telegram update processing failed.'));
            return response()->json(['ok' => false], 500);
        }
        return response()->json(['ok' => true]);
    }
}
