<?php

namespace App\Http\Controllers;


use App\Models\MonthCategory;
use App\Models\Transaction;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExchangeController extends Controller
{
    public function index()
    {
        $uahResponse = Http::get('https://api.coinbase.com/v2/prices/USDT-UAH/buy');
        $uah = $uahResponse->json();

        $cnyResponse = Http::get('https://api.coinbase.com/v2/prices/CNY-USDT/buy');
        $cnyUsd = $cnyResponse->json();

        $usdResponse = Http::get('https://api.coinbase.com/v2/prices/USDT-CNY/buy');
        $usdCny = $usdResponse->json();

        return view('exchange', compact('uah', 'cnyUsd', 'usdCny'));
    }

    public function tickerRate(Request $request)
    {
        $response = Http::get(sprintf('https://api.coinbase.com/v2/prices/%s-%s/buy', $request->get('fromTicker'), $request->get('toTicker')));

        if ($response->successful()) {
            $data = $response->json();
        } else {
            $data = ['error' => true];
        }

        return response()->json($data);
    }
}
