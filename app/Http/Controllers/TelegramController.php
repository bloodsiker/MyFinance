<?php

namespace App\Http\Controllers;

use App\Services\TelegramExchangeBot\TelegramBot;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TelegramController extends Controller
{
    public function webhook(Request $request, TelegramBot $telegramBot)
    {
        $telegramBot->run($request);

        return new Response('');
    }
}
