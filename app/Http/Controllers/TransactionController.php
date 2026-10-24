<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function list(Request $request)
    {
        $users = User::all();
        $categories = Category::all();

        $query = Transaction::query()->with(['category', 'user']);

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

        $spend = $query->sum('amount');

        $transactions = $query->orderByDesc('created_at')->paginate(50);

        return view('transactions_all', compact('transactions', 'users', 'categories', 'spend'));
    }

    public function create(Request $request)
    {
        $transaction = new Transaction();
        $transaction->category_id = $request->get('category_id');
        $transaction->month_id = $request->get('month_id');
        $transaction->user_id = Auth::user()->id;
        $transaction->title = $request->get('title');
        $transaction->amount = $request->get('amount');
        $transaction->type = $request->get('type');
        $transaction->created_at = $request->get('created_at');
        $transaction->save();

        return redirect()->back()->with('success', 'Транзакцію додано');
    }

    public function update(Request $request)
    {
        $transaction = Transaction::findOrFail($request->get('id'));
        $transaction->category_id = $request->get('category_id');
        $transaction->title = $request->get('title');
        $transaction->amount = $request->get('amount');
        $transaction->type = $request->get('type');
        $transaction->created_at = $request->get('created_at');
        $transaction->save();

        return redirect()->back()->with('success', 'Транзакцію змінено');
    }

    public function delete($id)
    {
        Transaction::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Транзакцію видалено');
    }

    public function getTransaction(Request $request)
    {
        $transaction = Transaction::findOrFail($request->get('id'));

        return response()->json($transaction);
    }

    public function getBudgetAnalytics(Request $request)
    {
        $categoryId = $request->get('category_id');
        $year = $request->get('year_id');

        $monthlySum = Transaction::groupBy('month_id')
            ->selectRaw('month_id, SUM(amount) as total_amount')
            ->where('category_id', $categoryId)
            ->whereHas('month', function ($query) use ($year) {
                $query->where('year_id', $year);
            })
            ->get();

        $amount = $monthlySum->avg('total_amount');

        return response()->json(['amount' => round($amount, 2)]);
    }
}
