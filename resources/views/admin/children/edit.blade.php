@extends('layout/' . $layout)
@section('subhead')
<title>Редагувати мастер пароль</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center h-10 mb-6">
                        <h1 class="section-h1">Редагувати {{ $children->last_name }} {{ $children->name }} {{ $children->middle_name }}</h1>
                    </div>
                    <div class="intro-y box">
                        <div class="p-5">
                            <div class="preview">
                                <form action="{{ route('admin.children.edit', ['id' => $children->id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div>Пакет навчання</div>
                                    <select class="form-select mt-2 sm:mr-2" name="study" aria-label="Пакет навчання">
                                        @foreach(App\Helpers\Constants::$choiceTariffs as $key => $tariff)
                                            <option value="{{ $key }}" @if($key == $children->study) selected @endif>{{ $tariff }}</option>
                                        @endforeach
                                    </select>
                                    <div class="h-2"></div>
                                    <div>Ім'я</div>
                                    <div>
                                        <input name="name" id="name-{{$children->id}}" onchange="checkName(`name-{{$children->id}}`)" type="text" class="form-control" value="{{$children->name}}" placeholder="ім'я" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}">
                                    </div>
                                    <div class="h-2"></div>
                                    <div>Прізвище</div>
                                    <div>
                                        <input name="last_name" id="last_name-{{$children->id}}" onchange="checkName(`last_name-{{$children->id}}`)" type="text" class="form-control" value="{{$children->last_name}}" placeholder="Прізвище" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}">
                                    </div>
                                    <div class="h-2"></div>
                                    <div>По батькові</div>
                                    <div>
                                        <input name="middle_name" id="middle_name-{{$children->id}}" onchange="checkName(`middle_name-{{$children->id}}`)" type="text" class="form-control" value="{{$children->middle_name}}" placeholder="По батькові" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}">
                                    </div>
                                    <div class="h-2"></div>
                                    <div>Телефон</div>
                                    <div>
                                        <input name="phone" id="phone-{{$children->id}}" onchange="checkPhone('phone-{{$children->id}}')" title="Номер телефону." pattern="[0-9+]{9,13}" type="tel" class="form-control" value="{{$children->phone}}" placeholder="Телефон">
                                    </div>
                                    <div class="h-2"></div>
                                    <div >Електронна пошта</div>
                                    <div>
                                        <input name="email" id="email-{{$children->id}}" onchange="checkEmail('email-{{$children->id}}')" type="text" class="form-control" value="{{$children->email}}" placeholder="Електронна пошта">
                                    </div>

                                    <div class="h-2"></div>
                                    <div class="flex">
                                        <div style="width: 20%; margin-right: 2%;">
                                            <img src="{{ asset($children->photo) }}" alt="">
                                        </div>
                                        <div style="width: 80%">
                                            <label for="file">
                                                <div class="dropzone">
                                                    <div class="fallback">
                                                        <input onchange="test(`file`)" accept="image/*" style="display: block" id="file" name="photo" type="file">
                                                    </div>
                                                    <div class="dz-message" data-dz-message>
                                                        <div class="text-lg font-medium">Натисніть, щоб завантажити фото дитини.</div>
                                                        <div class="flex w-100 justify-start" style="max-height: 150px;height: 150px">
                                                            <div class="flex">
                                                                <img id="file-file" data-dz-thumbnail="" style="border-radius: 20px; width:auto;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mt-5 flex justify-between">
                                        <button class="btn btn-primary mt-5">Зберегти</button>
                                        <a href="{{ route('admin.parent.children', ['id' => $children->user->id]) }}" class="btn btn-success mt-5">Назад</a>
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

<script>
    function checkEmail(id) {
        let field = document.getElementById(id);
        let reg1 = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;
        if (reg1.test(field.value) === false) {
            field.style.borderColor = 'red';
        } else {
            field.style.borderColor = "";
        }
    }
    function checkPhone(id) {
        let field = document.getElementById(id);
        let count = field.value.length;
        if (9 <= count && count <= 13 && /^[0-9+]+$/.test(field.value)) {
            field.style.borderColor = "";
        } else {
            field.style.borderColor = 'red';
        }
    }
    function checkName(id) {
        let field   = document.getElementById(id);
        let count   = field.value.length;
        let reg1    = /^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ’'`-]+$/;
        if (reg1.test(field.value) === true && count>1) {
            field.style.borderColor = "";
        } else {
            field.style.borderColor = 'red';
        }
    }
    function checkNumber(id, max) {
        let field = document.getElementById(id);
        let count = field.value.length;
        if (count <= 3 && /^[0-9]+$/.test(field.value) && field.value <= max && field.value > 0) {
            field.style.borderColor = "";
        } else {
            field.style.borderColor = 'red';
        }
    }

    function test(id) {
        var file = document.getElementById(id).files[0];
        var preview = document.getElementById(id+'-file');
        var reader  = new FileReader();
        if (document.getElementById(id).files[0].size > 5252484) {
            preview.src = '';
            preview.alt = '';
            document.getElementById(id).files[0] = null;
            alert("Розмір файлу не повинен перевищувати 5мб!");
        } else {
            reader.onloadend = function () {
                preview.src = reader.result;
                preview.alt = document.getElementById(id).files[0].name;
            };
            if (file) {
                reader.readAsDataURL(file);
            }
        }
    }
</script>
