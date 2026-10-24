@extends('layout/' . $layout)
@section('subhead')
<title>Новини</title>
@endsection

@section('subcontent')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 ">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10"></div>
                <div align="center">
                    <div>
                        <div class="intro-y col-span-12 md:col-span-6 xl:col-span-4 box">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="border-b-2 dark:border-dark-5">ФІО</th>
                                    <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Номер</th>
                                    <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Почта</th>
                                    <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Статус</th>
                                    <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Формат</th>
                                    <th class="border-b-2 dark:border-dark-5 whitespace-nowrap"></th>
                                </tr>
                                <tr>
                                    <form action="" method="GET">
                                        <th class="border-b-2 dark:border-dark-5"><input type="text" name="fio" value="{{ request()->get('fio') }}" class="form-control"></th>
                                        <th class="border-b-2 dark:border-dark-5"><input type="text" name="phone" value="{{ request()->get('phone') }}" class="form-control"></th>
                                        <th class="border-b-2 dark:border-dark-5"><input type="text" name="email" value="{{ request()->get('email') }}" class="form-control"></th>
                                        <th class="border-b-2 dark:border-dark-5">
                                            <select class="form-control" name="disable" id="user-disabled">
                                                <option value=""></option>
                                                <option value="on" @if(request()->get('disable') == 'on') selected @endif>Активний</option>
                                                <option value="off" @if(request()->get('disable') == 'off') selected @endif>Заблокований</option>
                                            </select>
                                        </th>
                                        <th class="border-b-2 dark:border-dark-5">
                                            <select class="form-control" name="training_format" id="training_format">
                                                <option value=""></option>
                                                @foreach(App\Helpers\Constants::$trainingFormats as $key => $format)
                                                    <option value="{{ $key }}" @if(request()->get('training_format') == $key) selected @endif>{{ $format }}</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th class="border-b-2 dark:border-dark-5 text-right">
                                            <button class="btn btn-primary">Search</button>
                                            <a href="{{ route('admin.parents') }}" class="btn btn-elevated-secondary">Clear</a>
                                        </th>
                                    </form>
                                </tr>
                                </thead>
                                @foreach($users as $user)
                                    <tbody>
                                    <tr>
                                        <td class="border-b dark:border-dark-5">{{$user->full_name}}</td>
                                        <td class="border-b dark:border-dark-5">{{$user->phone}}</td>
                                        <td class="border-b dark:border-dark-5">{{$user->email}}</td>
                                        <td class="border-b dark:border-dark-5 text-center">
                                            @if($user->disable)
                                                <div class="user-enable" data-id="{{ $user->id }}">
                                                    <span class="label label-danger">
                                                       Заблокований
                                                    </span>
                                                </div>
                                            @else
                                                <div class="user-disable" data-id="{{ $user->id }}">
                                                    <span class="label label-success">
                                                        Активний
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="border-b dark:border-dark-5 text-center">
                                            {{ $user->training_format_name }}
                                        </td>
                                        <td class="border-b dark:border-dark-5 text-right">
                                            <div class="flex justify-end">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle btn btn-primary" aria-expanded="false">Меню</button>
                                                    <div class="dropdown-menu w-40">
                                                        <div class="dropdown-menu__content box dark:bg-dark-1 p-2">
                                                            <a href="{{ route('admin.parent.children', ['id' => $user->id]) }}" class="block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">Діти</a>
                                                            <a href="{{ route('admin.sms_history', ['id' => $user->id]) }}" class="block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">SMS</a>
                                                            <a href="{{ route('admin.payment_history', ['id' => $user->id]) }}" class="block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">Payment</a>
                                                            <a href="{{ route('admin.login_as_user', ['id' => $user->id]) }}" class="block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">ReLogin</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
{{--                                            <a class="btn btn-xs btn-primary" href="{{ route('admin.parent.children', ['id' => $user->id]) }}">Діти</a>--}}
{{--                                            <a class="btn btn-xs btn-primary" href="{{ route('admin.sms_history', ['id' => $user->id]) }}">SMS</a>--}}
{{--                                            <a class="btn btn-xs btn-primary" href="{{ route('admin.payment_history', ['id' => $user->id]) }}">Payment</a>--}}
                                        </td>
                                    </tr>
                                    </tbody>
                                @endforeach
                            </table>

                            {{ $users->withQueryString()->links('admin.pagination.custom') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.user-enable', function (e) {
        let id = $(this).data('id');
        let _this = $(this);

        $.ajax({
            method: "POST",
            url: '{{ route('admin.toggle_enable_user') }}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { active: 0, id: id}
        }).done(function (response) {
            if (response.status == 'success') {
                _this.removeClass('user-enable').addClass('user-disable');
                _this.find('.label').removeClass('label-danger').addClass('label-success').text('Активний');
            } else {
                alert(response.message)
            }
        });
    })

    $(document).on('click', '.user-disable', function (e) {
        let id = $(this).data('id');
        let _this = $(this);

        $.ajax({
            method: "POST",
            url: '{{ route('admin.toggle_enable_user') }}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { active: 1, id: id }
        }).done(function( response ) {
            if (response.status == 'success') {
                _this.removeClass('user-disable').addClass('user-enable');
                _this.find('.label').removeClass('label-success').addClass('label-danger').text('Заблокований');
            } else {
                alert(response.message)
            }
        });
    })
</script>

@endsection
