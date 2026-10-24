@extends('layout/' . $layout)
@section('subhead')
<title>Категорії]</title>
@endsection

@section('subcontent')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 ">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10 mb-6 justify-between">
                    <h1 class="section-h1">Список мастер паролів</h1>
                    <div>
                        <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#addCategory">
                            Додати категорію
                        </a>
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
                            <th class="border-b-2 dark:border-dark-5">Категорія</th>
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td class="border-b dark:border-dark-5">{{ $category->name }}</td>
                                <td class="border-b dark:border-dark-5 text-right">
{{--                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.settings.general.list') }}">Відкрити</a>--}}
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

<div id="addCategory" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('category.create') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Додати категорію</h2>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12 sm:col-span-12">
                        <label for="name" class="form-label">Назва</label>
                        <input id="name" type="text" class="form-control" name="name" placeholder="Продукти" required>
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
