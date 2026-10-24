@extends('../layout/' . $layout)

@section('subhead')
    <title>Процес реєстрації</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xxl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: General Report -->
                <div class="col-span-12">
                    @if (session()->has('success'))
                        <div class="alert show alert-success mb-4">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <div class="col-span-12 mt-5">
                        <div class="intro-y block sm:flex items-center h-10">
                            <div class="flex">
                                @foreach($years as $year)
                                    @if($month->year->id == $year->id)
                                        <a href="#" onclick="return false;" class="btn-green active">{{ $year->name }}</a>
                                    @else
                                        <a href="{{ route('month.list', ['id' => $year->id]) }}" class="btn-green">{{ $year->name }}</a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="intro-y block sm:flex items-center h-10">
                            <div class="flex">
                                @foreach($months as $monthSingle)
                                    @if($month->id == $monthSingle->id)
                                        <a href="#" onclick="return false;" class="btn-green active">{{ $monthSingle->name }}</a>
                                    @else
                                        <a href="{{ route('transactions.list', ['id' => $monthSingle->id]) }}" class="btn-green">{{ $monthSingle->name }}</a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <hr class="mt-5 mb-5" style="border-bottom: 1px solid #e2e8f0">
                        <div class="intro-y block sm:flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">
                                {{ $month->year->name }} {{ $month->name }} (<span class="color-green">Бюджет: {{ number_format($monthBudget, 2, '.', ' ') }} грн</span> / <span class="color-red">Витрачено: {{ number_format($monthSpend, 2, '.', ' ') }} грн</span>)
                            </h2>
                            <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                                <a href="javascript:;" class="btn btn-success box flex items-center text-gray-700 dark:text-gray-300" data-toggle="modal" data-target="#addTransaction">
                                    Додати транзакцію
                                </a>
                            </div>
                        </div>
                        <hr class="mt-5 mb-5" style="border-bottom: 1px solid #e2e8f0">
                        <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                            <table class="table table-report sm:mt-2">
                                <thead>
                                <tr>
                                    <th class="whitespace-nowrap">Категорія</th>
                                    <th class="whitespace-nowrap">Назва</th>
                                    <th class="text-center whitespace-nowrap">Ціна</th>
                                    <th class="text-center whitespace-nowrap">Тип</th>
                                    <th class="text-center whitespace-nowrap">Автор</th>
                                    <th class="text-center whitespace-nowrap">Дата</th>
                                    <th class="text-center whitespace-nowrap"></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="intro-x">
                                        <form action="" method="GET">
                                            <td class="w-40">
                                                <div class="flex">
                                                    <select class="form-control" name="category_id" id="filter-category">
                                                        <option value=""></option>
                                                        @foreach($infoSpend as $category)
                                                            <option value="{{ $category['id'] }}" @if(request()->get('category_id') == $category['id']) selected @endif>{{ $category['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="title" value="{{ request()->get('title') }}" class="form-control">
                                            </td>
                                            <td class="text-center">
                                                <input type="text" value="{{ number_format($filterSpend, 2, '.', ' ') }} грн" readonly class="form-control text-center">
                                            </td>
                                            <td class="w-40">
                                                <div class="flex items-center justify-center text-theme-9">
                                                    <select class="form-control" name="type" id="filter-type">
                                                        <option value=""></option>
                                                        <option value="card" @if(request()->get('type') == 'card') selected @endif>Карта</option>
                                                        <option value="cache" @if(request()->get('type') == 'cache') selected @endif>Готівка</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="w-40">
                                                <div class="flex items-center justify-center">
                                                    <select class="form-control" name="user_id" id="filter-user">
                                                        <option value=""></option>
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}" @if(request()->get('user_id') == $user->id) selected @endif>{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="table-report__action" colspan="2">
                                                <div class="flex justify-center items-center">
                                                    <button class="btn btn-primary">Filter</button>
                                                    <a href="{{ route('transactions.list', ['id' => $month->id]) }}" class="btn btn-elevated-secondary">Clear</a>
                                                </div>
                                            </td>
                                        </form>
                                    </tr>
                                @foreach ($transactions as $transaction)
                                    <tr class="intro-x">
                                        <td class="w-40">
                                            <div class="flex">
                                                <div class="text-gray-600 text-xs whitespace-nowrap mt-0.5">{{ $transaction->category->name }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="javascript:;" data-id="{{ $transaction->id }}" data-toggle="modal" data-target="#editTransaction" class="font-medium transaction-edit">{{ $transaction->title }}</a>
                                        </td>
                                        <td class="text-center">{{ number_format($transaction->amount, 2, '.', ' ') }} грн</td>
                                        <td class="w-40">
                                            <div class="flex items-center justify-center text-theme-9">
                                                {{ $transaction->type == 'card' ? 'Карта' : 'Готівка' }}
                                            </div>
                                        </td>
                                        <td class="w-40">
                                            <div class="flex items-center justify-center">
                                                <div class="text-gray-600 text-xs whitespace-nowrap mt-0.5">{{ $transaction->user->name }}</div>
                                            </div>
                                        </td>
                                        <td class="table-report__action">
                                            <div class="flex justify-center items-center">
                                                <div class="text-gray-600 text-xs mt-0.5">{{ $transaction->created_at->format('Y-m-d H:i') }}</div>
                                            </div>
                                        </td>
                                        <td class="table-report__action">
                                            <a class="flex items-center text-theme-6" href="{{ route('transactions.delete', ['id' => $transaction->id]) }}">
                                                <i data-feather="trash-2" class="w-4 h-4 mr-1"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END: General Report -->
            </div>
        </div>

        <div class="col-span-12 xxl:col-span-3">
            <div class="xxl:border-l border-theme-5 -mb-10 pb-10">
                <div class="xxl:pl-6 grid grid-cols-12 gap-6">
                    <!-- BEGIN: Transactions -->
                    <div class="col-span-12 md:col-span-6 xl:col-span-4 xxl:col-span-12 mt-3 xxl:mt-6">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Бюджет на місяць</h2>
                            <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                                <a href="javascript:;" class="btn btn-success box flex items-center text-gray-700 dark:text-gray-300" data-toggle="modal" data-target="#addBudget">
                                    Додати
                                </a>
                            </div>
                        </div>
                        <div class="mt-5">
                            @foreach ($infoSpend as $monthCategory)
                                <div class="intro-x">
                                    <a href="javascript:;" class="budget-edit" data-category-id="{{ $monthCategory['id'] }}" data-toggle="modal" data-target="#editBudget">
                                        <div class="box px-3 py-3 mb-3 flex items-center zoom-in @if($monthCategory['spend'] > $monthCategory['budget']) bg-red @endif ">
                                            <div class="mr-auto">
                                                <div class="font-medium">{{ $monthCategory['name'] }}</div>
                                                <div class="text-gray-600 text-xs mt-0.5">Витрачено: </div>
                                            </div>
                                            <div>
                                            <span class="text-theme-9">
                                                {{ number_format($monthCategory['budget'], 2, '.', ' ') }} грн
                                            </span>
                                            <br>
                                            <span class="text-theme-6">
                                                {{ number_format($monthCategory['spend'], 2, '.', ' ') }} грн
                                            </span>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- END: Transactions -->
                </div>
            </div>
        </div>
    </div>

    <div id="addTransaction" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('transactions.create') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">Додати транзакцію</h2>
                    </div>
                    <input type="hidden" name="month_id" value="{{ $month->id }}">
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 sm:col-span-12">
                            <label for="transaction_category" class="form-label">Категорія</label>
                            <select class="form-select" name="category_id" id="transaction_category">
                                @foreach($monthCategories as $monthCategory)
                                    <option value="{{ $monthCategory->category->id }}">{{ $monthCategory->category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12 sm:col-span-12">
                            <label for="title" class="form-label">Опис</label>
                            <input id="title" type="text" class="form-control" name="title" placeholder="Покупка продуктів" required>
                        </div>

                        <div class="col-span-12 sm:col-span-12">
                            <label for="amount" class="form-label">Ціна</label>
                            <input id="amount" type="number" class="form-control" step="0.01" name="amount" placeholder="250" required>
                        </div>

                        <div class="col-span-12 sm:col-span-12">
                            <label for="type" class="form-label">Тип</label>
                            <select class="form-select" name="type" id="type">
                                <option value="card">Карта</option>
                                <option value="cash">Готівка</option>
                            </select>
                        </div>
                        <div class="col-span-12 sm:col-span-12">
                            <label for="created_at" class="form-label">Дата</label>
                            <input id="created_at" type="datetime-local" class="form-control" name="created_at" value="{{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}" placeholder="01.01.2024 " required>
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

    <div id="editTransaction" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('transactions.update') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">Змінити транзакцію</h2>
                    </div>
                    <input type="hidden" id="edit_transaction_id" name="id" value="">
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 sm:col-span-12">
                            <label for="edit_category" class="form-label">Категорія</label>
                            <select class="form-select" name="category_id" id="edit_category">
                                @foreach($monthCategories as $monthCategory)
                                    <option value="{{ $monthCategory->category->id }}">{{ $monthCategory->category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12 sm:col-span-12">
                            <label for="edit_title" class="form-label">Опис</label>
                            <input id="edit_title" type="text" class="form-control" name="title" placeholder="Покупка продуктів" required>
                        </div>

                        <div class="col-span-12 sm:col-span-12">
                            <label for="edit_amount" class="form-label">Ціна</label>
                            <input id="edit_amount" type="number" class="form-control" step="0.01" name="amount" placeholder="250" required>
                        </div>

                        <div class="col-span-12 sm:col-span-12">
                            <label for="edit_type" class="form-label">Тип</label>
                            <select class="form-select" name="type" id="edit_type">
                                <option value="card">Карта</option>
                                <option value="cash">Готівка</option>
                            </select>
                        </div>
                        <div class="col-span-12 sm:col-span-12">
                            <label for="edit_created_at" class="form-label">Дата</label>
                            <input id="edit_created_at" type="datetime-local" class="form-control" name="created_at" value="" placeholder="01.01.2024 " required>
                        </div>
                    </div>
                    <div class="modal-footer text-right">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                        <button class="btn btn-primary w-20">Змінити</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="addBudget" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('month.category.create') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">Додати бюджет</h2>
                    </div>
                    <input type="hidden" name="month_id" value="{{ $month->id }}">
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 sm:col-span-12">
                            <label for="category" class="form-label">Категорія</label>
                            <select class="form-select" name="category_id" id="category" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12 sm:col-span-12">
                            <label for="budget" class="form-label">Бюджет</label>
                            <input id="budget" type="number" class="form-control" name="budget" placeholder="2000" required>
                        </div>
                        <div class="col-span-12 sm:col-span-12">
                            <label for="budget_analytics" class="form-label">Бюджет розрахований на попередніх витратах за рік</label>
                            <input id="budget_analytics" type="number" class="form-control budget_analytics" name="budget_analytics" value="0" placeholder="2000" readonly>
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

    <div id="editBudget" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('month.category.update') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">Змінити бюджет <b class="category-name"></b></h2>
                    </div>
                    <input type="hidden" id="edit_category_id" name="category_id" value="">
                    <input type="hidden" name="month_id" value="{{ $month->id }}">
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 sm:col-span-12">
                            <label for="edit_budget" class="form-label">Бюджет</label>
                            <input id="edit_budget" type="number" class="form-control" name="budget" placeholder="2000" required>
                        </div>
                        <div class="col-span-12 sm:col-span-12">
                            <label for="budget_analytics" class="form-label">Бюджет розрахований на попередніх витратах за рік</label>
                            <input id="budget_analytics" type="number" class="form-control budget_analytics" name="budget_analytics" value="0" placeholder="2000" readonly>
                        </div>
                    </div>
                    <div class="modal-footer text-right">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                        <button class="btn btn-primary w-20">Змінити</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('footer_scripts')
    <script>
        $(document).on('click', '.budget-edit', function () {
            let monthId = '{{ $month->id }}';
            let categoryId = $(this).data('category-id');
            getBudgetAnalytics(categoryId);

            $.ajax({
                method: "POST",
                url: '{{ route('month.category.get') }}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: { month_id: monthId, category_id: categoryId}
            }).done(function (response) {
                $('#edit_budget').val(response.budget);
                $('#edit_category_id').val(categoryId);
                $('.category-name').text(response.category.name)
            });
        })

        $(document).on('click', '.transaction-edit', function () {
            let transactionId = $(this).data('id');

            $.ajax({
                method: "POST",
                url: '{{ route('transactions.get') }}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: { id: transactionId}
            }).done(function (response) {
                $('#edit_transaction_id').val(transactionId);
                $('#edit_category').val(response.category_id);
                $('#edit_title').val(response.title);
                $('#edit_amount').val(response.amount);
                $('#edit_type').val(response.type);
                var date = new Date(response.created_at);
                $('#edit_created_at').val(date.toISOString().slice(0,16));
            });
        })

        $(document).on('change', '#category', function () {
            let categoryId = $(this).val();

            getBudgetAnalytics(categoryId);
        })

        $(document).ready(function() {
            let categoryId = $("#category").val();

            getBudgetAnalytics(categoryId);
        });

        const getBudgetAnalytics = (categoryId) => {
            let yearId = '{{ $month->year->id }}';
            $('.budget_analytics').val(0);
            $.ajax({
                method: "POST",
                url: '{{ route('transactions.budget.analytics') }}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: { year_id: yearId, category_id: categoryId}
            }).done(function (response) {
                $('.budget_analytics').val(response.amount);
            });
        }
    </script>
@endpush
