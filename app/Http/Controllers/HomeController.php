<?php

namespace App\Http\Controllers;


use App\Models\MonthCategory;
use App\Models\Transaction;
use App\Models\Year;

class HomeController extends Controller
{
    public function dashboard()
    {
        $years = Year::all();
        $lastTransaction = Transaction::with('category')->orderByDesc('created_at')->take(10)->get();

        $info = [];
        foreach ($years as $year) {
            $info[$year->id]['year'] = $year;
            $info[$year->id]['budget'] = MonthCategory::query()
                ->join('months as m', 'month_category.month_id', '=', 'm.id')
                ->join('years as y', 'm.year_id', '=', 'y.id')
                ->where('y.id', $year->id)->sum('month_category.budget');
            $info[$year->id]['spend'] = Transaction::query()
                ->join('months as m', 'transactions.month_id', '=', 'm.id')
                ->join('years as y', 'm.year_id', '=', 'y.id')
                ->where('y.id', $year->id)->sum('transactions.amount');
        }

        return view('dashboard', compact('years', 'lastTransaction', 'info'));
    }
}
