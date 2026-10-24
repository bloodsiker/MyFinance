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
                    <h1 class="section-h1">Діти {{ $user->last_name }} {{ $user->user_name }} {{ $user->middle_name }}</h1>
                    <div>
                        <a href="{{ route('children.add', ['user_id' => $user->id]) }}" class="btn btn-primary">Додати учня</a>
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
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">FIO</th>
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Email</th>
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Phone</th>
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Class</th>
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Team</th>
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Study</th>
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap"></th>
                        </tr>
                        </thead>
                        @foreach($user->childrens as $children)
                            <tbody>
                            <tr>
                                <td class="border-b dark:border-dark-5">{{ $children->last_name }} {{ $children->name }} {{ $children->middle_name }}</td>
                                <td class="border-b dark:border-dark-5">{{ $children->email }}</td>
                                <td class="border-b dark:border-dark-5">{{ $children->phone }}</td>
                                <td class="border-b dark:border-dark-5">{{ $children->class }}</td>
                                <td class="border-b dark:border-dark-5">{{ $children->team }}</td>
                                <td class="border-b dark:border-dark-5">{{ $children->study }}</td>
                                <td class="border-b dark:border-dark-5 text-right">
                                    @if($children->step)
                                        <a class="btn btn-xs btn-danger" href="{{ route('children.add.step', ['id' => $children->id]) }}">Завершити реєстрацію</a>
                                    @else
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.children.edit', ['id' => $children->id]) }}">Редагувати</a>
                                    @endif

                                    @if($children->bx_id && !$children->step)
                                        <a class="btn btn-xs btn-success" href="{{ route('admin.children.sync_bitrix', ['id' => $children->id]) }}">Оновити в Bitrix</a>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        @endforeach
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
