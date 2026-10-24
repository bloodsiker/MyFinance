@extends('../layout/' . $layout)

@section('subhead')
    <title>Процес реєстрації</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xxl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: General Report -->
                <div class="col-span-12">

                    <div class="col-span-12 mt-5">
                        <div class="intro-y block sm:flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Всі транзакції</h2>
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
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="intro-x">
                                    <form action="" method="GET">
                                        <td class="w-40">
                                            <div class="flex">
                                                <select class="form-control" name="category_id" id="filter-category">
                                                    <option value=""></option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" @if(request()->get('category_id') == $category->id) selected @endif>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="title" value="{{ request()->get('title') }}" class="form-control">
                                        </td>
                                        <td class="text-center">
                                            <input type="text" value="{{ number_format($spend, 2, '.', ' ') }} грн" readonly class="form-control text-center">
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
                                        <td class="table-report__action">
                                            <div class="flex justify-center items-center">
                                                <button class="btn btn-primary">Filter</button>
                                                <a href="{{ route('transactions.all') }}" class="btn btn-elevated-secondary">Clear</a>
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
                                            <a href="" onclick="return false;" class="font-medium whitespace-nowrap">{{ $transaction->title }}</a>
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
                                                <div class="text-gray-600 text-xs mt-0.5">{{ $transaction->created_at->format('Y-m-d H:i:s') }}</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{ $transactions->withQueryString()->links('admin.pagination.custom') }}
                        </div>
                    </div>
                </div>
                <!-- END: General Report -->
            </div>
        </div>

@endsection
