@extends('layout/' . $layout)
@section('subhead')
<title>Мастер пароль</title>
@endsection

@section('subcontent')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 ">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10 mb-6 justify-between">
                    <h1 class="section-h1">Список мастер паролів</h1>
                    <div>
                        <a href="{{ route('admin.settings.list') }}" class="btn btn-primary">Назад</a>
                        <a href="{{ route('admin.settings.master_pass.create') }}" class="btn btn-primary">Додати пароль</a>
                    </div>
                </div>

                @if (session()->has('success'))
                    <div class="alert show alert-success mb-4">
                        {{ session()->get('success') }}
                    </div>
                @endif

                <div class="intro-y col-span-12 md:col-span-6 xl:col-span-4 box">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="border-b-2 dark:border-dark-5">Пароль</th>
                            <th class="border-b-2 dark:border-dark-5">Статус</th>
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap"></th>
                        </tr>
                        </thead>
                            <tbody>
                            @foreach($list as $item)
                                <tr>
                                    <td class="border-b dark:border-dark-5">{{ $item->password }}</td>
                                    <td class="border-b dark:border-dark-5">
                                        @if($item->is_active)
                                            <span class="label label-success">Активний</span>
                                        @else
                                            <span class="label label-danger">Не активний</span>
                                        @endif
                                    </td>
                                    <td class="border-b dark:border-dark-5 text-right">
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.settings.master_pass.edit', ['id' => $item->id]) }}">Редагувати</a>
                                        <a class="btn btn-xs btn-danger" href="{{ route('admin.settings.master_pass.delete', ['id' => $item->id]) }}">Видалити</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
