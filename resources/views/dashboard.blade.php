@extends('../layout/' . $layout)

@section('subhead')
    <title>Процес реєстрації</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xxl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: General Report -->
                <div class="col-span-12 mt-8">
                    @if (session()->has('success'))
                        <div class="alert show alert-success mb-4">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">Рік</h2>
                        <a href="javascript:;" class="ml-auto flex items-center text-theme-1 dark:text-theme-10" data-toggle="modal" data-target="#addYear">
                            <i data-feather="plus" class="w-4 h-4 mr-1"></i> Додати рік
                        </a>
                    </div>
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        @foreach($info as $year)
                            <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                                <div class="report-box zoom-in">
                                    <div class="box p-5">
                                        <a href="{{ route('month.list', ['id' => $year['year']->id]) }}">
                                            <div class="flex">
                                                <i data-feather="shopping-cart" class="report-box__icon text-theme-10"></i>
                                                <div class="ml-auto">
                                                    <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer fz-16" title="{{ $year['year']->name }}">
                                                        {{ $year['year']->name }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-2xl font-bold leading-8 mt-6">Бюджет: {{ number_format($year['budget'], 2, '.', ' ') }} <small>грн</small></div>
                                            <div class="text-base text-gray-600 mt-1">Витрати: {{ number_format($year['spend'], 2, '.', ' ') }} <small>грн</small></div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- END: General Report -->
                <!-- BEGIN: Sales Report -->
{{--                <div class="col-span-12 lg:col-span-9 mt-8">--}}
{{--                    <div class="intro-y block sm:flex items-center h-10">--}}
{{--                        <h2 class="text-lg font-medium truncate mr-5">Sales Report</h2>--}}
{{--                        <div class="sm:ml-auto mt-3 sm:mt-0 relative text-gray-700 dark:text-gray-300">--}}
{{--                            <i data-feather="calendar" class="w-4 h-4 z-10 absolute my-auto inset-y-0 ml-3 left-0"></i>--}}
{{--                            <input type="text" class="datepicker form-control sm:w-56 box pl-10">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="intro-y box p-5 mt-12 sm:mt-5">--}}
{{--                        <div class="flex flex-col xl:flex-row xl:items-center">--}}
{{--                            <div class="flex">--}}
{{--                                <div>--}}
{{--                                    <div class="text-theme-19 dark:text-gray-300 text-lg xl:text-xl font-bold">$15,000</div>--}}
{{--                                    <div class="mt-0.5 text-gray-600 dark:text-gray-600">This Month</div>--}}
{{--                                </div>--}}
{{--                                <div class="w-px h-12 border border-r border-dashed border-gray-300 dark:border-dark-5 mx-4 xl:mx-6"></div>--}}
{{--                                <div>--}}
{{--                                    <div class="text-gray-600 dark:text-gray-600 text-lg xl:text-xl font-medium">$10,000</div>--}}
{{--                                    <div class="mt-0.5 text-gray-600 dark:text-gray-600">Last Month</div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="dropdown xl:ml-auto mt-5 xl:mt-0">--}}
{{--                                <button class="dropdown-toggle btn btn-outline-secondary font-normal" aria-expanded="false">--}}
{{--                                    Filter by Category <i data-feather="chevron-down" class="w-4 h-4 ml-2"></i>--}}
{{--                                </button>--}}
{{--                                <div class="dropdown-menu w-40">--}}
{{--                                    <div class="dropdown-menu__content box dark:bg-dark-1 p-2 overflow-y-auto h-32">--}}
{{--                                        <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">PC & Laptop</a>--}}
{{--                                        <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">Smartphone</a>--}}
{{--                                        <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">Electronic</a>--}}
{{--                                        <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">Photography</a>--}}
{{--                                        <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">Sport</a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="report-chart">--}}
{{--                            <canvas id="report-line-chart" height="169" class="mt-6"></canvas>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <!-- END: Sales Report -->--}}
{{--                <!-- BEGIN: Weekly Top Seller -->--}}
{{--                <div class="col-span-12 sm:col-span-6 lg:col-span-3 mt-8">--}}
{{--                    <div class="intro-y flex items-center h-10">--}}
{{--                        <h2 class="text-lg font-medium truncate mr-5">Weekly Top Seller</h2>--}}
{{--                        <a href="" class="ml-auto text-theme-1 dark:text-theme-10 truncate">Show More</a>--}}
{{--                    </div>--}}
{{--                    <div class="intro-y box p-5 mt-5">--}}
{{--                        <canvas class="mt-3" id="report-pie-chart" height="300"></canvas>--}}
{{--                        <div class="mt-8">--}}
{{--                            <div class="flex items-center">--}}
{{--                                <div class="w-2 h-2 bg-theme-11 rounded-full mr-3"></div>--}}
{{--                                <span class="truncate">17 - 30 Years old</span>--}}
{{--                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>--}}
{{--                                <span class="font-medium xl:ml-auto">62%</span>--}}
{{--                            </div>--}}
{{--                            <div class="flex items-center mt-4">--}}
{{--                                <div class="w-2 h-2 bg-theme-1 rounded-full mr-3"></div>--}}
{{--                                <span class="truncate">31 - 50 Years old</span>--}}
{{--                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>--}}
{{--                                <span class="font-medium xl:ml-auto">33%</span>--}}
{{--                            </div>--}}
{{--                            <div class="flex items-center mt-4">--}}
{{--                                <div class="w-2 h-2 bg-theme-12 rounded-full mr-3"></div>--}}
{{--                                <span class="truncate">>= 50 Years old</span>--}}
{{--                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>--}}
{{--                                <span class="font-medium xl:ml-auto">10%</span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <!-- END: Weekly Top Seller -->
            </div>
        </div>
        <div class="col-span-12 xxl:col-span-3">
            <div class="xxl:border-l border-theme-5 -mb-10 pb-10">
                <div class="xxl:pl-6 grid grid-cols-12 gap-6">
                    <!-- BEGIN: Transactions -->
                    <div class="col-span-12 md:col-span-6 xl:col-span-4 xxl:col-span-12 mt-3 xxl:mt-8">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Останні витрати</h2>
                        </div>
                        <div class="mt-5">
                            @foreach ($lastTransaction as $transaction)
                                <div class="intro-x">
                                    <div class="box px-3 py-3 mb-3 flex items-center zoom-in">
                                        <div class="mr-auto">
                                            <div class="font-medium">{{ $transaction->title }}</div>
                                            <div class="text-gray-600 text-xs mt-0.5">{{ $transaction->created_at->format('Y-m-d H:i') }}</div>
                                        </div>
                                        <div class="text-theme-9">- {{ number_format($transaction->amount , 2, '.', ' ') }} грн</div>
                                    </div>
                                </div>
                            @endforeach
                            <a href="{{ route('transactions.all') }}" class="intro-x w-full block text-center rounded-md py-3 border border-dotted border-theme-15 dark:border-dark-5 text-theme-16 dark:text-gray-600">Всі транзакції</a>
                        </div>
                    </div>
                    <!-- END: Transactions -->
                </div>
            </div>
        </div>
    </div>

    <div id="addYear" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('year.create') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">Додати рік</h2>
                    </div>
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 sm:col-span-12">
                            <label for="year" class="form-label">Рік</label>
                            <input id="year" type="number" class="form-control" name="year" placeholder="2023" required>
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
