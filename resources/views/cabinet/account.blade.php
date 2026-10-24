@extends('../layout/' . $layout)

@section('subhead')
    <title>Акаунт</title>
@endsection

@section('subcontent')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center justify-end h-10">
                        <a href="{{ route('children.add') }}" class="btn btn-primary">Додати учня</a>
                    </div>
                    <div align="center">
                        <div class="boxed-tabs nav nav-tabs justify-center border border-gray-400 border-dashed text-gray-600 dark:border-gray-700 dark:bg-dark-3 dark:text-gray-500 rounded-md p-1 mt-5 mx-auto" role="tablist">
                            <a onclick="tabFormParent()" data-toggle="tab" data-target="#inactive-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2 active" id="inactive-users-tab" role="tab" aria-selected="true">{{$data['parent']->last_name.' '.$data['parent']->user_name}}</a>
                            @foreach($data['children'] as $key => $children)
                                    <a data-toggle="tab" onclick="tabForm({{$children->id}})" data-target="#active-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2" id="active-users-tab" role="tab" aria-controls="active-users" aria-selected="false">{{$children->name}}</a>
                            @endforeach
                        </div>

                        <div class="intro-y box mt-5 "  id="user-{{$data['parent']->id}}">
                            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
                                <h2 class="font-medium text-base mr-auto">В цьому розділі ви можете відредагувати внесену вами інформацію</h2>
                                <div class="form-check w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                                </div>
                            </div>
                            <div id="input-sizing" class="p-5">
                                <div class="preview">

                                    <form action="{{route('user-update')}}" method="post">
                                        @csrf
                                        <div style="height: 10px"></div>
                                        <div align="left">Ім'я</div>
                                        <div>
                                            <input required name="user_name" id="user_name" onchange="checkName('user_name')" type="text" class="form-control" value="{{$data['parent']->user_name}}" placeholder="ім'я" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}">
                                        </div>

                                        <div style="height: 10px"></div>
                                        <div align="left">Прізвище</div>
                                        <div>
                                            <input required name="last_name" id="last_name" onchange="checkName('last_name')" type="text" class="form-control" value="{{$data['parent']->last_name}}" placeholder="Прізвище" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}">
                                        </div>

                                        <div style="height: 10px"></div>
                                        <div align="left">По батькові</div>
                                        <div>
                                            <input required name="middle_name" id="middle_name" onchange="checkName('middle_name')" type="text" class="form-control" value="{{$data['parent']->middle_name}}" placeholder="По батькові" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}">
                                        </div>

                                        <div style="height: 10px"></div>
                                        <div align="left">Телефон</div>
                                        <div>
                                            <input required name="phone" id="phone" onchange="checkPhone('phone')" type="tel"  title="Номер телефону." pattern="[0-9+]{9,13}"  class="form-control" value="{{$data['parent']->phone}}" placeholder="Телефон">
                                        </div>

                                        <div style="height: 10px"></div>
                                        <div align="left">Електронна пошта</div>
                                        <div>
                                            <input required name="email" id="email" onchange="checkEmail('email')" type="text" class="form-control" value="{{$data['parent']->email}}" placeholder="Електронна пошта">
                                        </div>

                                        <div style="height: 10px"></div>
                                        <div align="left">Місто</div>
                                        <div>
                                            <input required name="city" type="text" class="form-control" value="{{$data['parent']->city}}" placeholder="Місто">
                                        </div>

                                        <div style="height: 10px"></div>
                                        <div align="left">Вулиця</div>
                                        <div>
                                            <input required name="street" type="text" class="form-control" value="{{$data['parent']->street}}" placeholder="Вулиця">
                                        </div>

                                        <div style="height: 10px"></div>
                                        <div align="left">№ дому</div>
                                        <div>
                                            <input required name="house" type="text" class="form-control" value="{{$data['parent']->house}}" placeholder="№ дому">
                                        </div>

                                        <div style="height: 10px"></div>
                                        <div align="left">Нова пошта</div>
                                        <div>
                                            <input required name="post" type="text" class="form-control" value="{{$data['parent']->post}}" placeholder="Нова пошта">
                                        </div>

                                        <div style="height: 10px"></div>
                                        <div align="left">Канал зв'язку</div>
                                        <select name="communication" style="background-color: white" class="form-control w-full">
                                            <option {{$data['parent']->communication==='viber'?'selected':""}} value="viber">вайбер</option>
                                            <option {{$data['parent']->communication==='telegram'?'selected':""}} value="telegram">телеграм</option>
                                            <option {{$data['parent']->communication==='email'?'selected':""}} value="email">email</option>
                                            <option {{$data['parent']->communication==='sms'?'selected':""}} value="sms">смс</option>
                                        </select>

                                        <div style="height: 10px"></div>
                                        <div align="left">Посилання на завантажжені документи</div>
                                        <div style="height: 10px"></div>
                                        <div align="left">
                                            <a style="color: #1D68CD" href="{{$data['parent']->passport}}" target="_blank">Фото паспорта одного з батьків</a><br>
                                            @foreach($data['children'] as $children)
                                                @if($children->certificate !== null)
                                                    <a style="color: #1D68CD" href="{{$children->certificate}}" target="_blank">Фото свідоцтва про народження дитини({{$children->name}})</a><br>
                                                @endif
                                            @endforeach
                                            @foreach($data['children'] as $children)
                                                @if($children->statement !== null)
                                                    <a style="color: #1D68CD" href="{{$children->statement}}" target="_blank">Фото заяви до школи({{$children->name}})</a><br>
                                                @endif
                                            @endforeach
                                            @foreach($data['children'] as $children)
                                                @if($children->photo !== null)
                                                    <a style="color: #1D68CD" href="{{$children->photo}}" target="_blank">Фотокартка дитини({{$children->name}})</a><br>
                                                @endif
                                            @endforeach
                                        </div>
                                        <button class="btn btn-warning w-24 mr-1 mb-2">Зберегти</button>
                                    </form>

                                </div>
                            </div>
                        </div>

                        <div>
                            @foreach($data['children'] as $key => $children)
                                <div class="intro-y box mt-5" id="children_{{$children->id}}">
                                    <script>
                                        document.getElementById('children_{{$children->id}}').style = 'display: none;';
                                    </script>
                                    <div id="faq-accordion-20-{{$children->id}}" class="accordion p-4">
                                        @if($children->step)
                                            <a href="{{ route('children.add.step', ['id' => $children->id]) }}" class="btn btn-danger">Завершити реестрацію учня {{ $children->name }} {{$children->last_name}} {{$children->middle_name}}</a>
                                        @else
                                            <form action="{{route('children-update')}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div align="left">Ім'я</div>
                                                <div>
                                                    <input name="name" id="name-{{$children->id}}" onchange="checkName(`name-{{$children->id}}`)" type="text" class="form-control" value="{{$children->name}}" placeholder="ім'я" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}">
                                                </div>
                                                <div style="height: 10px"></div>
                                                <div align="left">Прізвище</div>
                                                <div>
                                                    <input name="last_name" id="last_name-{{$children->id}}" onchange="checkName(`last_name-{{$children->id}}`)" type="text" class="form-control" value="{{$children->last_name}}" placeholder="Прізвище" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}">
                                                </div>
                                                <div style="height: 10px"></div>
                                                <div align="left">По батькові</div>
                                                <div>
                                                    <input name="middle_name" id="middle_name-{{$children->id}}" onchange="checkName(`middle_name-{{$children->id}}`)" type="text" class="form-control" value="{{$children->middle_name}}" placeholder="По батькові" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}">
                                                </div>
                                                <div style="height: 10px"></div>
                                                <div align="left">Телефон</div>
                                                <div>
                                                    <input name="phone" id="phone-{{$children->id}}" onchange="checkPhone('phone-{{$children->id}}')" title="Номер телефону." pattern="[0-9+]{9,13}" type="tel" class="form-control" value="{{$children->phone}}" placeholder="Телефон">
                                                </div>
                                                <div style="height: 10px"></div>
                                                <div align="left">Електронна пошта</div>
                                                <div>
                                                    <input name="email" id="email-{{$children->id}}" onchange="checkEmail('email-{{$children->id}}')" type="text" class="form-control" value="{{$children->email}}" placeholder="Електронна пошта">
                                                </div>

                                                <div style="height: 10px"></div>
                                                <div class="flex">
                                                    <div style="width: 20%; margin-right: 2%;">
                                                        <img src="{{ asset($children->photo) }}" alt="">
                                                    </div>
                                                    <div style="width: 80%">
                                                        <label for="file">
                                                            <div class="dropzone">
                                                                <div class="fallback">
                                                                    <input onchange="test(`file-{{ $children->id }}`)" accept="image/*" style="display: block" id="file-{{ $children->id }}" name="photo" type="file">
                                                                </div>
                                                                <div class="dz-message" data-dz-message>
                                                                    <div class="text-lg font-medium">Натисніть, щоб завантажити фото дитини.</div>
                                                                    <div class="flex w-100 justify-start" style="max-height: 150px;height: 150px">
                                                                        <div class="flex">
                                                                            <img id="preview-file-{{ $children->id }}" data-dz-thumbnail="" style="border-radius: 20px; width:auto;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div style="height: 10px"></div>
                                                <div align="left">Клас</div>
                                                <div align="left">
                                                    <p>{{$children->class}}</p>
                                                </div>
                                                <div style="height: 10px"></div>
                                                <div align="left">Команда</div>
                                                <div align="left">
                                                    <p>{{$children->team}}</p>
                                                </div>
                                                <button name="id" value="{{$children->id}}" class="btn btn-warning w-24 mr-1 mb-2">Зберегти</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
<script>
    function tabForm(id) {
        @foreach($data['children'] as $value)
        document.getElementById('children_{{$value['id']}}').style = 'display: none;';
        @endforeach
        document.getElementById(`children_${id}`).style = '';
        document.getElementById("user-{{$data['parent']->id}}").style = 'display: none;';
    }

    function tabFormParent() {
        @foreach($data['children'] as $value)
        document.getElementById('children_{{$value['id']}}').style = 'display: none;';
        @endforeach
        document.getElementById("user-{{$data['parent']->id}}").style = '';
    }
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
        var preview = document.getElementById('preview-'+id);
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
