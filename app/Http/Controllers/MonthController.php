<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Month;
use App\Models\MonthCategory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthController extends Controller
{
    public function index($id, Request $request)
    {
        $user = Auth::user();
        $user->last_url = $request->getUri();
        $user->save();

        $month = Month::find($id);
        $months = Month::where('year_id', $month->year_id)->get();
        $years = Year::all();
        $monthCategories = MonthCategory::with('category')->where('month_id', $id)->get();
        $categoryIds = $monthCategories->pluck('category_id')->all();
        $categories = Category::whereNotIn('id', $categoryIds)->get();
//        $transactions = Transaction::with(['category', 'user'])->where('month_id', $id)->orderByDesc('created_at')->get();

        $users = User::all();

        $infoSpend = [];
        foreach ($monthCategories as $monthCategory) {
            $category = $monthCategory->category;
            $infoSpend[$category->id]['id'] = $category->id;
            $infoSpend[$category->id]['name'] = $category->name;
            $infoSpend[$category->id]['budget'] = $monthCategory->budget;
            $infoSpend[$category->id]['spend'] = Transaction::where('month_id', $id)->where('category_id', $category->id)->sum('amount');
        }

        $query = Transaction::with(['category', 'user'])->where('month_id', $id);

        if ($request->get('title')) {
            $query->where('title', 'LIKE', '%'.$request->get('title').'%');
        }

        if ($request->get('user_id')) {
            $query->where('user_id', '=', $request->get('user_id'));
        }

        if ($request->get('category_id')) {
            $query->where('category_id', '=', $request->get('category_id'));
        }

        if ($request->get('type')) {
            $query->where('type', '=', $request->get('type'));
        }

        $query->orderByDesc('created_at');

        $monthBudget = MonthCategory::where('month_id', $id)->sum('budget');
        $monthSpend = Transaction::where('month_id', $id)->sum('amount');
        $filterSpend = $query->sum('amount');

        $transactions = $query->get();

        return view('transactions', compact('month', 'categories', 'transactions',
            'monthCategories', 'months', 'infoSpend', 'monthBudget', 'monthSpend', 'filterSpend', 'users', 'years')
        );
    }

    public function create(Request $request)
    {
        $budget = new MonthCategory();
        $budget->month_id = $request->get('month_id');
        $budget->category_id = $request->get('category_id');
        $budget->budget = $request->get('budget');
        $budget->save();

        return redirect()->back()->with('success', 'Бюджет додано');
    }

    public function update(Request $request)
    {
        $budget = MonthCategory::where('month_id', $request->get('month_id'))
            ->where('category_id', $request->get('category_id'))
            ->first();
        $budget->budget = $request->get('budget');
        $budget->update();

        return redirect()->back()->with('success', 'Бюджет змінено');
    }

    public function getBudget(Request $request)
    {
        $budget = MonthCategory::with('category')->where('month_id', $request->get('month_id'))
            ->where('category_id', $request->get('category_id'))
            ->first();

        return response()->json($budget);
    }
}
