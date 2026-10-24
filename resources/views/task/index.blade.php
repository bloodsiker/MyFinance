@extends('../layout/' . $layout)

@section('subhead')
    <title>Task</title>
@endsection

@section('subcontent')
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Задачи на {{ $selectedDay }}</h2>
    </div>
    <div class="grid grid-cols-12 gap-5 mt-5">

        @if (session()->has('success'))
            <div class="col-span-12">
                <div class="alert show alert-success mb-4">
                    {{ session()->get('success') }}
                </div>
            </div>
        @endif

        <!-- BEGIN: Calendar Side Menu -->
        <div class="col-span-12 xl:col-span-4 xxl:col-span-4">
            <div class="box p-5 intro-y">
                <div class="flex">
                    <span class="switch-month cursor-pointer" data-url="{{ route('task.list_by_date', ['date' => $prevMonth->format('Y-m-d'), 'day' => $prevMonth->format('Y-m-d')]) }}">
                         <i data-feather="chevron-left" class="w-5 h-5 text-gray-600"></i>
                    </span>
                    <div class="font-medium text-base mx-auto">{{ $nameMonth }}</div>
                    <span class="switch-month cursor-pointer" data-url="{{ route('task.list_by_date', ['date' => $nextMonth->format('Y-m-d'), 'day' => $nextMonth->format('Y-m-d')]) }}">
                         <i data-feather="chevron-right" class="w-5 h-5 text-gray-600"></i>
                    </span>
                </div>
                <div class="grid grid-cols-7 gap-4 mt-5 text-center">
                    <div class="font-medium">Пн</div>
                    <div class="font-medium">Вт</div>
                    <div class="font-medium">Ср</div>
                    <div class="font-medium">Чт</div>
                    <div class="font-medium">Пт</div>
                    <div class="font-medium">Сб</div>
                    <div class="font-medium">Вс</div>
                    @foreach ($weeks as $week)
                        @foreach ($week as $day)
                            <div class="switch-day py-0.5 rounded relative cursor-pointer
                            @if($day == Carbon\Carbon::now()->format('Y-m-d')) bg-theme-1 dark:bg-theme-1 text-white @endif
                            @if($selectedDay == $day) bg-theme-18 dark:bg-theme-9 @endif
                            @if($month != (int) \Carbon\Carbon::parse($day)->format('m')) text-gray-600 @endif"
                                 data-url="{{ route('task.list_by_date', ['date' => $selectedDate->format('Y-m-d'), 'day' => $day]) }}">
                                {{ \Carbon\Carbon::parse($day)->format('j') }}
                            </div>
                        @endforeach
                    @endforeach
{{--                    <div class="py-0.5 rounded relative text-gray-600">29</div>--}}
{{--                    <div class="py-0.5 rounded relative text-gray-600">30</div>--}}
{{--                    <div class="py-0.5 rounded relative text-gray-600">31</div>--}}
{{--                    <div class="py-0.5 rounded relative">1</div>--}}
{{--                    <div class="py-0.5 rounded relative">2</div>--}}
{{--                    <div class="py-0.5 rounded relative">3</div>--}}
{{--                    <div class="py-0.5 rounded relative">4</div>--}}
{{--                    <div class="py-0.5 rounded relative">5</div>--}}
{{--                    <div class="py-0.5 bg-theme-18 dark:bg-theme-9 rounded relative">6</div>--}}
{{--                    <div class="py-0.5 rounded relative">7</div>--}}
{{--                    <div class="py-0.5 bg-theme-1 dark:bg-theme-1 text-white rounded relative">8</div>--}}
{{--                    <div class="py-0.5 rounded relative">9</div>--}}
{{--                    <div class="py-0.5 rounded relative">10</div>--}}
{{--                    <div class="py-0.5 rounded relative">11</div>--}}
{{--                    <div class="py-0.5 rounded relative">12</div>--}}
{{--                    <div class="py-0.5 rounded relative">13</div>--}}
{{--                    <div class="py-0.5 rounded relative">14</div>--}}
{{--                    <div class="py-0.5 rounded relative">15</div>--}}
{{--                    <div class="py-0.5 rounded relative">16</div>--}}
{{--                    <div class="py-0.5 rounded relative">17</div>--}}
{{--                    <div class="py-0.5 rounded relative">18</div>--}}
{{--                    <div class="py-0.5 rounded relative">19</div>--}}
{{--                    <div class="py-0.5 rounded relative">20</div>--}}
{{--                    <div class="py-0.5 rounded relative">21</div>--}}
{{--                    <div class="py-0.5 rounded relative">22</div>--}}
{{--                    <div class="py-0.5 bg-theme-17 dark:bg-theme-11 rounded relative">23</div>--}}
{{--                    <div class="py-0.5 rounded relative">24</div>--}}
{{--                    <div class="py-0.5 rounded relative">25</div>--}}
{{--                    <div class="py-0.5 rounded relative">26</div>--}}
{{--                    <div class="py-0.5 bg-theme-14 dark:bg-theme-12 rounded relative">27</div>--}}
{{--                    <div class="py-0.5 rounded relative">28</div>--}}
{{--                    <div class="py-0.5 rounded relative">29</div>--}}
{{--                    <div class="py-0.5 rounded relative">30</div>--}}
{{--                    <div class="py-0.5 rounded relative text-gray-600">1</div>--}}
{{--                    <div class="py-0.5 rounded relative text-gray-600">2</div>--}}
{{--                    <div class="py-0.5 rounded relative text-gray-600">3</div>--}}
{{--                    <div class="py-0.5 rounded relative text-gray-600">4</div>--}}
{{--                    <div class="py-0.5 rounded relative text-gray-600">5</div>--}}
{{--                    <div class="py-0.5 rounded relative text-gray-600">6</div>--}}
{{--                    <div class="py-0.5 rounded relative text-gray-600">7</div>--}}
{{--                    <div class="py-0.5 rounded relative text-gray-600">8</div>--}}
{{--                    <div class="py-0.5 rounded relative text-gray-600">9</div>--}}
                </div>
            </div>

        </div>
        <!-- END: Calendar Side Menu -->
        <!-- BEGIN: Calendar Content -->
        <div class="col-span-12 xl:col-span-4 xxl:col-span-4">
            <div class="box p-5 intro-y">
                <div class="intro-y flex items-center text-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">{{ $dima->name }}</h2>
                </div>
                <a href="javascript:;" class="btn btn-primary w-full mt-2 addTask" data-user="{{ $dima->id }}" data-toggle="modal" data-target="#addTask">
                    <i class="w-4 h-4 mr-2" data-feather="edit-3"></i> Добавить задачу
                </a>
                <div class="border-t border-b border-gray-200 dark:border-dark-5 mt-6 mb-3 py-3" id="calendar-events">
                    @foreach($listTaskDima as $taskDima)
                        <div class="relative">
                            <div class="event p-3 -mx-3 cursor-pointer transition duration-300 ease-in-out hover:bg-gray-200 dark:hover:bg-dark-1 rounded-md flex items-center">
                                <div class="w-2 h-2 @if($taskDima->is_completed) bg-theme-9 @else bg-theme-6  @endif  rounded-full mr-3"></div>
                                <div class="pr-10">
                                    <div class="event__title truncate">{{ $taskDima->description }}</div>
                                    <div class="text-gray-600 text-xs mt-0.5">
                                        <span class="event__days">{{ $taskDima->priority }}</span> @if($taskDima->task_time) <span class="mx-1">•</span> {{ $taskDima->task_time->format('H:i') }}@endif
                                    </div>
                                </div>
                            </div>
                            <a class="flex taskCompleted items-center @if($taskDima->is_completed)text-theme-6 @else text-theme-9 @endif absolute top-0 bottom-0 my-auto right-42" data-url="{{ route('task.completed', ['id' => $taskDima->id]) }}" href="javascript:;">
                                @if($taskDima->is_completed)
                                    <i data-feather="thumbs-down" class="w-4 h-4 mr-1"></i>
                                @else
                                    <i data-feather="thumbs-up" class="w-4 h-4 mr-1"></i>
                                @endif
                            </a>
                            <a class="flex items-center absolute top-0 bottom-0 my-auto right-25" data-url="{{ route('task.completed', ['id' => $taskDima->id]) }}" href="javascript:;">
                                <i data-feather="edit" class="w-4 h-4 text-gray-600"></i>
                            </a>
                            <a class="flex taskDelete items-center text-theme-6 absolute top-0 bottom-0 my-auto right-0" data-url="{{ route('task.delete', ['id' => $taskDima->id]) }}" href="javascript:;">
                                <i data-feather="trash-2" class="w-4 h-4 mr-1"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="col-span-12 xl:col-span-4 xxl:col-span-4">
            <div class="box p-5 intro-y">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">{{ $vika->name }}</h2>
                </div>
                <a href="javascript:;" class="btn btn-primary w-full mt-2 addTask" data-user="{{ $vika->id }}" data-toggle="modal" data-target="#addTask">
                    <i class="w-4 h-4 mr-2" data-feather="edit-3"></i> Добавить задачу
                </a>
                <div class="border-t border-b border-gray-200 dark:border-dark-5 mt-6 mb-3 py-3" id="calendar-events">
                    @foreach($listTaskVika as $taskVika)
                        <div class="relative">
                            <div class="event p-3 -mx-3 cursor-pointer transition duration-300 ease-in-out hover:bg-gray-200 dark:hover:bg-dark-1 rounded-md flex items-center">
                                <div class="w-2 h-2 @if($taskVika->is_completed) bg-theme-9 @else bg-theme-6  @endif  rounded-full mr-3"></div>
                                <div class="pr-10">
                                    <div class="event__title truncate">{{ $taskVika->description }}</div>
                                    <div class="text-gray-600 text-xs mt-0.5">
                                        <span class="event__days">{{ $taskVika->priority }}</span> @if($taskVika->task_time) <span class="mx-1">•</span> {{ $taskVika->task_time->format('H:i') }}@endif
                                    </div>
                                </div>
                            </div>
                            <a class="flex taskCompleted items-center text-theme-6 absolute top-0 bottom-0 my-auto right-42" data-url="{{ route('task.completed', ['id' => $taskVika->id]) }}" href="javascript:;">
                                <i data-feather="thumbs-up" class="w-4 h-4 mr-1"></i>
                            </a>
                            <a class="flex items-center absolute top-0 bottom-0 my-auto right-25" data-url="{{ route('task.completed', ['id' => $taskVika->id]) }}" href="javascript:;">
                                <i data-feather="edit" class="w-4 h-4 text-gray-600"></i>
                            </a>
                            <a class="flex taskDelete items-center text-theme-6 absolute top-0 bottom-0 my-auto right-0" data-url="{{ route('task.delete', ['id' => $taskVika->id]) }}" href="javascript:;">
                                <i data-feather="trash-2" class="w-4 h-4 mr-1"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- END: Calendar Content -->
    </div>

    <div id="addTask" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('task.create') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">Додати задачу</h2>
                    </div>
                    <input type="hidden" id="taskUserId" name="user_id" value="">
                    <input type="hidden" name="task_date" value="{{ $selectedDay }}">
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 sm:col-span-12">
                            <label for="description" class="form-label">Что сделать</label>
                            <input id="description" type="text" class="form-control" name="description" placeholder="Попылесосить" required>
                        </div>

                        <div class="col-span-12 sm:col-span-12">
                            <label for="priority" class="form-label">Приоритет</label>
                            <select class="form-select" name="priority" id="priority" required>
                                <option value="high" selected>Высокий</option>
                                <option value="middle">Средний</option>
                                <option value="low">Низкий</option>
                            </select>
                        </div>

                        <div class="col-span-12 sm:col-span-12">
                            <label for="task_time" class="form-label">Время</label>
                            <input id="task_time" type="time" class="form-control" name="task_time" value="" placeholder="14:00">
                        </div>
                    </div>
                    <div class="modal-footer text-right">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                        <button class="btn btn-primary w-20">Додати</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script>
        $(document).on('click', '.addTask', function () {
            let userId = $(this).data('user');

            $('#taskUserId').val(userId);
        })

        $(document).on('click', '.taskDelete, .taskCompleted', function () {
            window.location = $(this).data('url');
        })

        $(document).on('click', '.switch-month', function () {
            window.location = $(this).data('url');
        })

        $(document).on('click', '.switch-day', function () {
            window.location = $(this).data('url');
        })
    </script>
@endpush
