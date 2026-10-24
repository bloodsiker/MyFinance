<?php

namespace App\Http\Controllers;

use App\Services\TelegramExchangeBot\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;

class TelegramController extends Controller
{
    public function webhook(Request $request, TelegramBot $telegramBot)
    {
        $telegramBot->run($request);

        return new Response('');
    }

    public function setWebhook(Client $client)
    {
        $response = $client->post('https://api.telegram.org/bot7194044173:AAHBmqQBA4pbn3xgYBAuX4bQDfchIyZn3rA/setWebhook', [
            'form_params' => [
                'url' => 'https://fin.naturalstones-jewerly.com.ua/webhook',
            ],
        ]);

        $body = $response->getBody()->getContents();

        dd(json_decode($body, true));
    }

    public function testBot(Request $request)
    {
        $balances = $this->getBalance();
        dd($balances);

        return new Response('');
    }


    protected function getBalance()
    {
        $response = Http::withHeaders([
            'X-Token' => 'uvZ6cJ1MQgiGeU15Sd48nN9WyG32H8T8HaAx92jtv0rQ',
        ])->get('https://api.monobank.ua/personal/client-info');

        $responseBody = $response->body();

        return json_decode($responseBody, true);
    }
}
