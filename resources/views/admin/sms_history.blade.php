@extends('layout/' . $layout)
@section('subhead')
<title>Новини</title>
@endsection

@section('subcontent')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 ">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10 mb-6">
                    <h1 class="section-h1">Відправлені смс {{ $user->last_name }} {{ $user->user_name }} {{ $user->middle_name }}</h1>
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
                <div class="grid grid-cols-12 gap-5 mt-5">
                    <div class="col-span-12 sm:col-span-6 xxl:col-span-3 box p-5 cursor-pointer zoom-in sendSms" data-step="1">
                        <div class="font-medium text-base">Відправити смс - Step 1</div>
                        <div class="text-gray-600">25 числа</div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xxl:col-span-3 box p-5 cursor-pointer zoom-in sendSms" data-step="2">
                        <div class="font-medium text-base">Відправити смс - Step 2</div>
                        <div class="text-gray-600">1 числа</div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xxl:col-span-3 box p-5 cursor-pointer zoom-in sendSms" data-step="3">
                        <div class="font-medium text-base">Відправити смс - Step 3</div>
                        <div class="text-gray-600">2 числа</div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xxl:col-span-3 box p-5 cursor-pointer zoom-in sendSms" data-step="4">
                        <div class="font-medium text-base">Відправити смс - Step 4</div>
                        <div class="text-gray-600">5 числа</div>
                    </div>
                </div>
                <div class="intro-y col-span-12 md:col-span-6 xl:col-span-4 box mt-5">
                    <div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Step</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Текст</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Дата</th>
                            </tr>
                            </thead>
                            @foreach($histories as $history)
                                <tbody>
                                <tr>
                                    <td class="border-b dark:border-dark-5">{{ $history->step }}</td>
                                    <td class="border-b dark:border-dark-5">{{ $history->text }}</td>
                                    <td class="border-b dark:border-dark-5">{{ $history->created_at }}</td>
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

<script>
    $('.sendSms').on('click', function () {
        let step = $(this).data('step');
        window.location.href = '{{ route('admin.send_sms_period', ['id' => $user->id]) }}' + '?step=step_' + step;
    })
</script>

@endsection
