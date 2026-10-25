<?php

namespace App\Http\Controllers;

use App\Models\{Category, Month, MonthCategory, Task, Transaction, User, Year};
use App\Services\FinanceTelegram\TelegramTransactionNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Http};
use Illuminate\Validation\Rule;

class FinanceCabinetController extends Controller
{
    public function __construct(private readonly TelegramTransactionNotifier $telegramNotifications)
    {
    }

    private const MONTH_NAMES = [
        1 => 'Січень', 2 => 'Лютий', 3 => 'Березень', 4 => 'Квітень',
        5 => 'Травень', 6 => 'Червень', 7 => 'Липень', 8 => 'Серпень',
        9 => 'Вересень', 10 => 'Жовтень', 11 => 'Листопад', 12 => 'Грудень',
    ];

    public function index(Request $request)
    {
        return view('material.app', ['bootstrap' => [
            'base' => url('/'), 'api' => url('/cabinet-api'), 'csrf' => csrf_token(),
            'user' => $request->user() ? $request->user()->only(['id', 'name', 'email']) : null,
            'timezone' => config('app.timezone', 'UTC'),
            'resetToken' => $request->route('token'), 'email' => $request->query('email', ''),
        ]]);
    }

    public function data(Request $request)
    {
        $input = $request->validate([
            'month_id' => 'nullable|integer|exists:months,id', 'year_id' => 'nullable|integer|exists:years,id',
            'category_id' => 'nullable|integer|exists:categories,id', 'user_id' => 'nullable|integer|exists:users,id',
            'title' => 'nullable|string|max:255', 'type' => ['nullable', Rule::in(['card', 'cash', 'cache'])],
            'page' => 'nullable|integer|min:1', 'date' => 'nullable|date_format:Y-m-d',
        ]);
        $budgets = MonthCategory::selectRaw('month_id, SUM(budget) as total')->groupBy('month_id')->pluck('total', 'month_id');
        $spent = Transaction::selectRaw('month_id, SUM(amount) as total')->groupBy('month_id')->pluck('total', 'month_id');
        $months = Month::orderByRaw('CAST(number AS UNSIGNED)')->get()->map(function ($month) use ($budgets, $spent) {
            return ['id' => $month->id, 'year_id' => $month->year_id, 'name' => $month->name, 'number' => (int) $month->number,
                'budget' => (float) ($budgets[$month->id] ?? 0), 'spent' => (float) ($spent[$month->id] ?? 0)];
        });
        $years = Year::orderByDesc('name')->get()->map(function ($year) use ($months) {
            $items = $months->where('year_id', $year->id);
            return ['id' => $year->id, 'name' => $year->name, 'budget' => $items->sum('budget'), 'spent' => $items->sum('spent')];
        });
        $query = Transaction::with(['category', 'user', 'month']);
        foreach (['month_id', 'category_id', 'user_id'] as $field) {
            if (!empty($input[$field])) $query->where($field, $input[$field]);
        }
        if (!empty($input['title'])) $query->where('title', 'like', '%'.$input['title'].'%');
        if (!empty($input['type'])) {
            $input['type'] === 'card' ? $query->where('type', 'card') : $query->whereIn('type', ['cash', 'cache']);
        }
        $filteredSpent = (float) (clone $query)->sum('amount');
        $monthId = $input['month_id'] ?? null;
        $query->orderByDesc('created_at')->orderByDesc('id');
        if ($monthId) {
            $items = $query->get();
            $transactions = ['items' => $items->map(fn ($row) => $this->transactionData($row)),
                'page' => 1, 'last_page' => 1, 'total' => $items->count(), 'spent' => $filteredSpent];
        } else {
            $paginated = $query->paginate(20);
            $transactions = ['items' => $paginated->getCollection()->map(fn ($row) => $this->transactionData($row)),
                'page' => $paginated->currentPage(), 'last_page' => $paginated->lastPage(), 'total' => $paginated->total(), 'spent' => $filteredSpent];
        }
        if ($monthId && $request->user()->last_url !== url('/month/'.$monthId)) {
            $request->user()->forceFill(['last_url' => url('/month/'.$monthId)])->save();
        }
        $categorySpent = $monthId ? Transaction::where('month_id', $monthId)->selectRaw('category_id, SUM(amount) as total')->groupBy('category_id')->pluck('total', 'category_id') : collect();
        $monthBudgets = $monthId ? MonthCategory::with('category')->where('month_id', $monthId)->get()->map(function ($row) use ($categorySpent) {
            return ['id' => $row->id, 'category_id' => $row->category_id, 'name' => optional($row->category)->name,
                'budget' => (float) $row->budget, 'spent' => (float) ($categorySpent[$row->category_id] ?? 0)];
        }) : collect();
        $date = $input['date'] ?? now()->toDateString();
        $tasks = Task::where('task_date', $date)->orderBy('is_completed')->orderBy('task_time')->get()->map(function ($task) {
            return ['id' => $task->id, 'user_id' => $task->user_id, 'description' => $task->description,
                'priority' => $task->priority, 'is_completed' => (bool) $task->is_completed,
                'task_date' => $task->task_date, 'task_time' => $task->task_time ? $task->task_time->format('H:i') : ''];
        });
        return response()->json([
            'years' => $years, 'months' => $months, 'categories' => Category::orderBy('name')->get(['id', 'name']),
            'users' => User::orderBy('name')->get(['id', 'name']), 'budgets' => $monthBudgets, 'tasks' => $tasks,
            'transactions' => $transactions,
            'recent' => Transaction::with(['category', 'user', 'month'])->orderByDesc('created_at')->limit(7)->get()->map(fn ($row) => $this->transactionData($row)),
        ]);
    }

    private function transactionData(Transaction $row): array
    {
        return ['id' => $row->id, 'month_id' => $row->month_id, 'category_id' => $row->category_id,
            'user_id' => $row->user_id, 'title' => $row->title, 'amount' => (float) $row->amount,
            'type' => $row->type === 'card' ? 'card' : 'cash', 'created_at' => $row->created_at->toIso8601String(),
            'created_at_local' => $row->created_at->copy()->setTimezone(config('app.timezone', 'UTC'))->format('Y-m-d\TH:i'),
            'category' => optional($row->category)->name, 'author' => optional($row->user)->name];
    }

    public function year(Request $request)
    {
        $data = $request->validate(['year' => 'required|integer|min:1900|max:9999|unique:years,name']);
        $year = DB::transaction(function () use ($data) {
            $year = Year::create(['name' => $data['year']]);
            foreach (self::MONTH_NAMES as $number => $name) {
                Month::create(['year_id' => $year->id, 'number' => $number, 'name' => $name]);
            }
            return $year;
        });
        return response()->json(['message' => 'Рік створено', 'id' => $year->id], 201);
    }

    public function category(Request $request, ?int $id = null)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        if ($id) {
            $category = Category::findOrFail($id);
            $category->update($data);
        } else {
            $category = Category::create($data);
        }
        return response()->json(['message' => $id ? 'Категорію оновлено' : 'Категорію додано', 'id' => $category->id], $id ? 200 : 201);
    }

    public function budget(Request $request)
    {
        $data = $request->validate(['month_id' => 'required|integer|exists:months,id',
            'category_id' => 'required|integer|exists:categories,id', 'budget' => 'required|numeric|min:0']);
        MonthCategory::updateOrCreate(['month_id' => $data['month_id'], 'category_id' => $data['category_id']], ['budget' => $data['budget']]);
        return response()->json(['message' => 'Бюджет збережено']);
    }

    public function analytics(Request $request)
    {
        $data = $request->validate(['year_id' => 'required|integer|exists:years,id', 'category_id' => 'required|integer|exists:categories,id']);
        $sums = Transaction::where('category_id', $data['category_id'])->whereHas('month', fn ($query) => $query->where('year_id', $data['year_id']))
            ->selectRaw('month_id, SUM(amount) as total')->groupBy('month_id')->get();
        return response()->json(['amount' => round((float) $sums->avg('total'), 2)]);
    }

    public function saveTransaction(Request $request, ?int $id = null)
    {
        $data = $request->validate(['month_id' => 'required|integer|exists:months,id', 'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|string|max:255', 'amount' => 'required|numeric|min:0', 'type' => ['required', Rule::in(['card', 'cash'])],
            'created_at' => 'required|date_format:Y-m-d\TH:i']);
        if ($id) {
            $transaction = Transaction::findOrFail($id);
            unset($data['month_id']); // Preserve the original month when editing.
            $transaction->fill($data)->save();
        } else {
            $transaction = Transaction::create($data + ['user_id' => $request->user()->id]);
            $this->telegramNotifications->notifySubscribers($transaction);
        }
        return response()->json(['message' => $id ? 'Транзакцію оновлено' : 'Транзакцію додано']);
    }

    public function deleteTransaction(int $id)
    {
        Transaction::findOrFail($id)->delete();
        return response()->json(['message' => 'Транзакцію видалено']);
    }

    public function task(Request $request)
    {
        $data = $request->validate(['user_id' => 'required|integer|exists:users,id', 'description' => 'required|string|max:10000',
            'priority' => ['required', Rule::in(['high', 'middle', 'low'])], 'task_date' => 'required|date_format:Y-m-d', 'task_time' => 'nullable|date_format:H:i']);
        Task::create($data);
        return response()->json(['message' => 'Задачу додано'], 201);
    }

    public function toggleTask(int $id)
    {
        $task = Task::findOrFail($id);
        $task->is_completed = !$task->is_completed;
        $task->save();
        return response()->json(['message' => $task->is_completed ? 'Задачу виконано' : 'Задачу повернуто до роботи']);
    }

    public function deleteTask(int $id)
    {
        Task::findOrFail($id)->delete();
        return response()->json(['message' => 'Задачу видалено']);
    }

    public function rates()
    {
        try {
            $rates = [];
            foreach (['cny_usdt' => 'CNY-USDT', 'usdt_cny' => 'USDT-CNY', 'usdt_uah' => 'USDT-UAH'] as $key => $pair) {
                $response = Http::timeout(8)->get('https://api.coinbase.com/v2/prices/'.$pair.'/buy');
                $amount = (float) $response->json('data.amount');
                if (!$response->successful() || $amount <= 0) throw new \RuntimeException('Invalid rate');
                $rates[$key] = $amount;
            }
            return response()->json($rates + ['updated_at' => now()->toIso8601String()]);
        } catch (\Throwable $exception) {
            return response()->json(['message' => 'Не вдалося отримати курси. Спробуйте оновити пізніше або введіть курс вручну.'], 502);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['url' => url('/login')]);
    }
}
