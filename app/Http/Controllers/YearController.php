<?php

namespace App\Http\Controllers;


use App\Models\Month;
use App\Models\MonthCategory;
use App\Models\Transaction;
use App\Models\Year;
use Illuminate\Http\Request;

class YearController extends Controller
{
    public function index($id, Request $request)
    {
        $year = Year::find($id);
        $years = Year::all();
        $months = Month::where('year_id', $id)->get();
        $info = [];
        foreach ($months as $month) {
            $info[$month->id]['month'] = $month;
            $info[$month->id]['budget'] = MonthCategory::where('month_id', $month->id)->sum('budget');
            $info[$month->id]['spend'] = Transaction::where('month_id', $month->id)->sum('amount');
        }

        return view('months', compact('year','years', 'info'));
    }

    public function create(Request $request)
    {
        $year = new Year();
        $year->name = $request->get('year');
        $year->save();

        foreach (self::getMonths() as $key => $name) {
            $month = new Month();
            $month->year_id = $year->id;
            $month->name = $name;
            $month->number = $key;
            $month->save();
        }

        return redirect()->back()->with('success', 'Рік додано');
    }

    public static function getMonths()
    {
        return [
            1 => 'Січень',
            2 => 'Лютий',
            3 => 'Березень',
            4 => 'Квітень',
            5 => 'Травень',
            6 => 'Червень',
            7 => 'Липень',
            8 => 'Серпень',
            9 => 'Вересень',
            10 => 'Жовтень',
            11 => 'Листопад',
            12 => 'Грудень',
        ];
    }
}
