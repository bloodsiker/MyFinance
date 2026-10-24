@extends('layout/' . $layout)
@section('subhead')
<title>Редагувати мастер пароль</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center h-10"></div>
                    <div class="intro-y box">
                        <div class="p-5">
                            <div class="preview">
                                <form action="{{ route('admin.settings.master_pass.edit', ['id' => $item->id]) }}" method="POST">
                                    @csrf
                                    <div class="mt-3">
                                        <label for="password" class="form-label">Пароль</label>
                                        <input id="password" name="password" type="text" class="form-control" placeholder="Пароль" value="{{ $item->password }}">
                                    </div>
                                    <div class="form-check mt-5">
                                        <input id="is_active" class="form-check-input" type="checkbox" name="is_active" @if($item->is_active) checked @endif value="1">
                                        <label class="form-check-label" for="is_active">Активний</label>
                                    </div>
                                    <div class="mt-5 flex justify-between">
                                        <button class="btn btn-primary mt-5">Зберегти</button>
                                        <a href="{{ route('admin.settings.master_pass.list') }}" class="btn btn-success mt-5">Назад</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
