@extends('layout/' . $layout)
@section('subhead')
<title>Загальні налаштування</title>
@endsection

@section('subcontent')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 ">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10 mb-6 justify-between">
                    <h1 class="section-h1">Загальні налаштування</h1>
                    <a href="{{ route('admin.settings.list') }}" class="btn btn-primary">Назад</a>
                </div>

                @if (session()->has('success'))
                    <div class="alert show alert-success mb-4">
                        {{ session()->get('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert show alert-danger mb-4">
                        {{ session()->get('error') }}
                    </div>
                @endif

                @foreach($list as $item)
                    @if($item->key === 'training_format')
                        <div class="intro-y box">
                            <div class="p-5">
                                <h2 class="section-h1">{{ $item->name }}</h2>
                                <hr class="mt-3 mb-3">
                                <div class="preview">
                                    <form action="{{ route('admin.settings.general.training_update') }}" method="POST">
                                        @csrf
                                        <select class="form-select mt-2 sm:mr-2" aria-label="{{ $item->name }}" name="{{ $item->key }}">
                                            @foreach(json_decode($item->setting) as $key => $value)
                                                <option value="{{ $key }}" @if($item->value === $key) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <div class="mt-5 flex justify-between">
                                            <button class="btn btn-primary mt-5">Зберегти</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
