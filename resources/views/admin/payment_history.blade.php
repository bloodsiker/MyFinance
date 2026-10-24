@extends('layout/' . $layout)
@section('subhead')
<title>Новини</title>
@endsection

@section('subcontent')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 ">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10 mb-6 justify-between">
                    <h1 class="section-h1">Платежі {{ $user->last_name }} {{ $user->user_name }} {{ $user->middle_name }}</h1>
                    <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#addTransaction">Додати транзакцію</a>
                </div>
                @if (session()->has('success'))
                    <div class="alert show alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert show alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif
                <div class="intro-y col-span-12 md:col-span-6 xl:col-span-4 box mt-5">
                    <div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Провайдер</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">ID платежа</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Сума</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Знижка</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Місяців</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Статус</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Оплачено до</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Дата створення</th>
                            </tr>
                            </thead>
                            @foreach($payments as $payment)
                                <tbody>
                                <tr>
                                    <td class="border-b dark:border-dark-5">{{ $payment->method }}</td>
                                    <td class="border-b dark:border-dark-5">{{ $payment->payment_id }}</td>
                                    <td class="border-b dark:border-dark-5">{{ $payment->amount }}</td>
                                    <td class="border-b dark:border-dark-5">
                                        Sale: {{ $payment->sale }} <br>
                                        Referal: {{ $payment->referal }}
                                    </td>
                                    <td class="border-b dark:border-dark-5">{{ $payment->months }}</td>
                                    <td class="border-b dark:border-dark-5">
                                        @if($payment->status == 'pay')
                                            <span class="label label-success"> {{ $payment->status }}</span>
                                        @elseif($payment->status == 'wait')
                                            <span class="label label-pending"> {{ $payment->status }}</span>
                                        @elseif($payment->status == 'fail')
                                            <span class="label label-danger"> {{ $payment->status }}</span>
                                        @endif
                                    </td>
                                    <td class="border-b dark:border-dark-5">{{ $payment->before }}</td>
                                    <td class="border-b dark:border-dark-5">{{ $payment->created_at }}</td>
                                </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="addTransaction" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.user.transaction_add', ['id' => $user->id]) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Додати транзакцію</h2>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12 sm:col-span-12">
                        <label for="before" class="form-label">Оплачено до</label>
                        <input id="before" type="date" class="form-control" name="before" placeholder="01.01.2024" required>
                    </div>
                    <div class="col-span-12 sm:col-span-12">
                        <label for="months" class="form-label">Кількість місяців</label>
                        <select class="form-select" name="months" id="months">
                            @foreach(range(1, 10) as $month)
                                <option @if($user->months == $month) selected @endif value="{{ $month }}">{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer text-right">
                    <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    <button class="btn btn-primary w-20">Додати</button>
                </div>
                <!-- END: Modal Footer -->
            </form>
        </div>
    </div>
</div>

@endsection
