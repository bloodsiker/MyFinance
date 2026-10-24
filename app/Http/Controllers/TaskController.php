<?php

namespace App\Http\Controllers;


use App\Models\Month;
use App\Models\MonthCategory;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Year;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TaskController extends Controller
{
    public function list(Request $request, ?string $date = null)
    {
        $selectedDate = $date ? Carbon::parse($date) : Carbon::now()->startOfMonth();
        $month = $selectedDate->month;
        $prevMonth = $selectedDate->copy()->subMonth()->startOfMonth();
        $nextMonth = $selectedDate->copy()->addMonth()->startOfMonth();
        $nameMonth = self::getMonths()[$month];
        $weeks = $this->getMonthWeeks($selectedDate->year, $selectedDate->month);
        $selectedDay = Carbon::now()->format('Y-m-d');
        $dima = User::find(2);
        $vika = User::find(3);

        $listTaskDima = Task::where(['user_id' => $dima->id, 'task_date' => $selectedDay])->orderByDesc('is_completed')->orderByDesc('task_time')->get();
        $listTaskVika = Task::where(['user_id' => $vika->id, 'task_date' => $selectedDay])->orderByDesc('is_completed')->orderByDesc('task_time')->get();

        return view('task.index', compact('weeks', 'nameMonth', 'month', 'selectedDate',
            'selectedDay', 'prevMonth', 'nextMonth', 'dima', 'vika', 'listTaskVika', 'listTaskDima'));
    }

    public function listByDate(Request $request, string $date)
    {
        $selectedDate = $date ? Carbon::parse($date) : Carbon::now()->startOfMonth();
        $month = $selectedDate->month;
        $prevMonth = $selectedDate->copy()->subMonth()->startOfMonth();
        $nextMonth = $selectedDate->copy()->addMonth()->startOfMonth();
        $nameMonth = self::getMonths()[$month];
        $weeks = $this->getMonthWeeks($selectedDate->year, $selectedDate->month);
        $dima = User::find(2);
        $vika = User::find(3);

        $listTaskDima = [];
        $listTaskVika = [];
        $selectedDay = $request->get('day');
        if ($selectedDay) {
            $listTaskDima = Task::where(['user_id' => $dima->id, 'task_date' => $selectedDay])->orderBy('is_completed', 'asc')->orderByDesc('task_time')->get();
            $listTaskVika = Task::where(['user_id' => $vika->id, 'task_date' => $selectedDay])->orderBy('is_completed', 'asc')->orderByDesc('task_time')->get();
        }

        return view('task.index', compact('weeks', 'nameMonth', 'month',
            'selectedDate', 'selectedDay', 'prevMonth', 'nextMonth', 'dima', 'vika', 'listTaskVika', 'listTaskDima'));
    }

    function getMonthWeeks($year, $month)
    {
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $weeks = [];
        $currentWeek = [];

        $currentDay = $startOfMonth->copy()->startOfWeek(CarbonInterface::MONDAY);

        while ($currentDay <= $endOfMonth || $currentDay->dayOfWeek !== CarbonInterface::MONDAY) {
            $currentWeek[] = $currentDay->format('Y-m-d');

            if ($currentDay->dayOfWeek === CarbonInterface::SUNDAY) {
                $weeks[] = $currentWeek;
                $currentWeek = [];
            }

            $currentDay->addDay();
        }

        if (!empty($currentWeek)) {
            $weeks[] = $currentWeek;
        }

        return $weeks;
    }

    public function completed(int $id)
    {
        $task = Task::find($id);
        $task->is_completed = !$task->is_completed;
        $task->save();

        return redirect()->back()->with('success', $task->is_completed ? 'Задачу виконано' : 'Задачу не виконано');
    }

    public function delete(int $id)
    {
        Task::find($id)->delete();

        return redirect()->back()->with('success', 'Задачу видалено');
    }

    public function create(Request $request)
    {
        $task = new Task();
        $task->description = $request->get('description');
        $task->priority = $request->get('priority');
        $task->user_id = $request->get('user_id');
        $task->task_time = $request->get('task_time');
        $task->task_date = $request->get('task_date');
        $task->save();

        return redirect()->back()->with('success', 'Задачу додано');
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
