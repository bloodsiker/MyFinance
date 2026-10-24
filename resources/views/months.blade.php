@extends('../layout/' . $layout)

@section('subhead')
    <title>Процес реєстрації</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xxl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: General Report -->
                <div class="col-span-12 mt-8">
                    @if (session()->has('success'))
                        <div class="alert show alert-success mb-4">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">Рік {{ $year->name }}</h2>
                        <div class="flex">
                            @foreach($years as $yearSingle)
                                @if($year->id == $yearSingle->id)
                                    <a href="#" onclick="return false;" class="btn-green active">{{ $yearSingle->name }}</a>
                                @else
                                    <a href="{{ route('month.list', ['id' => $yearSingle->id]) }}" class="btn-green">{{ $yearSingle->name }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        @foreach($info as $month)
                            <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                                <div class="report-box zoom-in @if($month['spend'] > $month['budget']) bg-red @endif">
                                    <div class="box p-5">
                                        <a href="{{ route('transactions.list', ['id' => $month['month']->id]) }}">
                                            <div class="flex">
                                                <div class="ml-auto">
                                                    <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer fz-16" title="{{ $month['month']->name }}">
                                                        {{ $month['month']->name }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-2xl font-bold leading-8 mt-6">Бюджет: {{ number_format($month['budget'], 2, '.', ' ') }} <small>грн</small></div>
                                            <div class="text-base text-gray-600 mt-1">Витрати: {{ number_format($month['spend'], 2, '.', ' ') }} <small>грн</small></div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- END: General Report -->
            </div>
        </div>
    </div>

@endsection
