@extends('../layout/' . $layout)

@section('subhead')
    <title>Процес реєстрації</title>
@endsection

@section('subcontent')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">

                    @include('progress', ['colors'=>['bg-theme-9','bg-theme-9','bg-theme-12','bg-gray-200','bg-gray-200']])

                    <div class="boxed-tabs nav nav-tabs justify-center border border-gray-400 border-dashed text-gray-600 dark:border-gray-700 dark:bg-dark-3 dark:text-gray-500 rounded-md p-1 mt-5 mx-auto" role="tablist">
                        @foreach($item as $key => $children)
                            @if($key===0)
                                <a onclick="tabForm({{$children['id']}})" data-toggle="tab" data-target="#inactive-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2 active" id="inactive-users-tab" role="tab" aria-selected="true">{{$children['name']}}</a>
                            @else
                                <a onclick="tabForm({{$children['id']}})" data-toggle="tab" data-target="#active-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2" id="active-users-tab" role="tab" aria-controls="active-users" aria-selected="false">{{$children['name']}}</a>
                            @endif
                        @endforeach

                    </div>
                    <div class="intro-y flex items-center h-10"></div>
                    <div class="intro-y flex items-center h-30">
                        <h2 style="width: 100%" class="text-center text-lg font-medium  mr-5">Круто, майже досягли цілі. Для створення особистого кабінету на навчальній платформі потрібна додаткова інформація. <br> Ви на найважливішому етапі реєстрації.
                            Прямуючи подальшими кроками, Ви проаналізуєте унікальність своєї дитини, поставите разом з нею цілі та оберете спосіб їх досягнення.</h2>
                    </div>
                    <br>
                    <div class="alert alert-warning show flex items-center mb-2" role="alert">
                        <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> Всі поля обовʼязкові для заповненя
                    </div>

                    <div class="intro-y flex items-center h-10"></div>

                    <form method="post" action="{{route('third_step')}}" enctype="multipart/form-data">

                        <div class="intro-y box p-5">
                            @foreach($item as $key => $children)
                                <div id="children_{{$children['id']}}">
                                    @if($key!==0)
                                        <script>
                                            document.getElementById('children_{{$children['id']}}').style = 'display: none;';
                                        </script>
                                    @endif

                                    @csrf

                                    <div id="faq-accordion-1" class="accordion p-5">

                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-1" class="accordion-header">
                                                <button class="accordion-button" type="button"   aria-expanded="true" >
                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                        <label style="width: 100%" class="text-lg font-medium truncate mr-5">ПІБ/Контакт</label>
                                                    </div>
                                                </button>
                                            </div>
                                            <div style="display: block;">
                                                <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                    <div id="faq-accordion-10-{{$children['id']}}" class="accordion p-5">
                                                        <br>
                                                        <div>
                                                            <label  for="regular-form-1" class="form-label">Прізвище учня</label>
                                                            <input onchange="checkName(`{{$children['id']}}[last_name]`)" required name="{{$children['id']}}[last_name]" id="{{$children['id']}}[last_name]" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}" type="text" class="form-control" placeholder="Прізвище учня">
                                                        </div>
                                                        <br>
                                                        <div>
                                                            <label for="regular-form-1" class="form-label">По батькові</label>
                                                            <input onchange="checkName(`{{$children['id']}}[middle_name]`)" required name="{{$children['id']}}[middle_name]" id="{{$children['id']}}[middle_name]" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}" type="text" class="form-control" placeholder="По батькові учня">
                                                        </div>
                                                        <br>
                                                        <div>
                                                            <label for="regular-form-1" class="form-label">Дата народження</label>
                                                            <div id="{{$children['id']}}-birth" class="preview">
                                                                <input required name="{{$children['id']}}[birth]" id="{{$children['id']}}[birth]" class="form-control" placeholder="дд.мм.рр">
                                                                {{--<input required name="{{$children['id']}}[birth]" id="{{$children['id']}}[birth]" class="datepicker form-control block mx-auto" data-single-mode="true" data-lang="uk-UA" buttonText="Choose">--}}
                                                            </div>
                                                            <script>
                                                            </script>
                                                        </div>

                                                        <div style="height: 15px"></div>
                                                        <label for="{{$children['id']}}-file">

                                                            <div class="dropzone">
                                                                <div class="fallback">
                                                                    <input onchange="test(`{{$children['id']}}-file`)" accept="image/*" required style="display: block" id="{{$children['id']}}-file" name="{{$children['id']}}[photo]" type="file">
                                                                </div>
                                                                <div class="dz-message" data-dz-message>
                                                                   <div class="text-lg font-medium">Натисніть, щоб завантажити фото дитини.  <br><em>( Обовʼязкове для завантаження )</em></div>
                                                                   <div class="flex w-100 justify-start" style="max-height: 150px;height: 150px">
                                                                       <div class="flex">
                                                                           <img id="{{$children['id']}}-file-file" data-dz-thumbnail="" style="border-radius: 20px; width:auto;">
                                                                       </div>
                                                                   </div>
                                                                </div>
                                                            </div>

                                                        </label>
                                                        <div style="height: 15px"></div>
                                                        <br>
                                                        <div class="alert alert-warning-soft show flex items-center mb-2" role="alert">
                                                            <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> Всі поля обовʼязкові для заповненя
                                                        </div>
                                                        <br>
                                                        <div>
                                                            <label for="{{$children['id']}}[email]" class="form-label">email</label>
                                                            <input onchange="checkEmail(`{{$children['id']}}[email]`)" required name="{{$children['id']}}[email]" id="{{$children['id']}}[email]" type="email" class="form-control" placeholder="email@gmail.com">
                                                        </div>
                                                        <br>
                                                        <div style="height: 10px"></div>
                                                        <div align="left">
                                                            <input onclick="readonly(`{{$children['id']}}[zoom-email]`)" name="{{$children['id']}}[zoom-email-check]" id="{{$children['id']}}[zoom-email-check]" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">
                                                            <label for="{{$children['id']}}[zoom-email-check]" class="form-label">я буду використовувати цей email для Google Meet</label>
                                                            <input style="display: none" id="previous-{{$children['id']}}[zoom-email]">
                                                        </div>
                                                        <br>
                                                        <div>
                                                            <label for="regular-form-1" class="form-label">email для Google Meet</label>
                                                            <input onchange="checkEmail(`{{$children['id']}}[zoom-email]`)" required name="{{$children['id']}}[zoom_email]" id="{{$children['id']}}[zoom-email]" type="email" class="form-control" placeholder="email@gmail.com">
                                                        </div>

                                                        <br>
                                                        <div>
                                                            <label for="{{$children['id']}}[phone]" class="form-label">Телефон учня</label>
                                                            <input id="{{$children['id']}}[phone]" data-user-id="{{ $children['id'] }}" required class="intl-phone form-control" name="{{$children['id']}}[virtual_phone]" type="tel">
                                                            <input type="hidden" class="user_phone" data-user-id="{{ $children['id'] }}" name="{{$children['id']}}[phone]">
                                                            <div class="text-left">
                                                                <span class="error-msg" data-user-id="{{ $children['id'] }}"></span>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div>
                                                            <label for="regular-form-1" class="form-label">клас</label>
                                                            <select onchange="setClassItems(`{{$children['id']}}`, `{{$children['name']}}`)" style="background-color: white" class="form-control w-full" name="{{$children['id']}}[class]" id="{{$children['id']}}[class]">
                                                                @for($i = 5; $i <= 11; $i++)
                                                                    <option value="{{$i}}">{{$i}}</option>
                                                                @endfor
                                                            </select>
                                                            <script>
                                                                document.addEventListener('DOMContentLoaded', function() {
                                                                    window.onload = setClassItems(`{{$children['id']}}`, `{{$children['name']}}`);
                                                                }, false);
                                                            </script>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-2" class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-2" aria-expanded="true" aria-controls="faq-accordion-collapse-2">
                                                    <div class="intro-y col-span-12 sm:col-span-6" style="z-index: 0">
                                                        <label for="input-wizard-1" class="text-lg form-label">Сильні сторони</label>
                                                    </div>
                                                </button>
                                            </div>
                                            <div id="faq-accordion-collapse-2"  aria-labelledby="faq-accordion-content-2" data-bs-parent="#faq-accordion-1" style="display:block">
                                                <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                    <div style="height: 15px"></div>
                                                    <div align="center">
                                                        <div style="height: 15px"></div>
                                                        <div class="alert alert-secondary show mr-3 training_format_item" role="alert">
                                                        <h3 style="width: 100%" class="text-center font-medium  mr-5"><b>Наша школа базується на множинних інтелектів </b> <br>Керуючись теорією множинних інтелектів Г.Гарднера, ми розробили опитування. Воно дозволяє визначити схильності дитини та унікальну комбінацію домінантних інтелектів. <br> Результати тесту допоможуть визначити сфери діяльності, до яких у Вашої дитини є найбільший хист на даному етапі її розвитку. <br> Це є основою ранньої профорієнтації та критерієм у виборі навчальних предметів для системного та глибинного опанування. <br> Даний тест має пройти Ваша дитина. </h3>
                                                            </div>
                                                        <div style="height: 15px"></div>

                                                        <a href="javascript:;" onclick="resetTest({{ $children['id'] }})" data-toggle="modal" data-target="#question_{{ $children['id'] }}" type="button" class="btn btn-outline-success">Пройти тест на множинні інтелекти</a>


                                                        <div id="question_{{ $children['id'] }}" class="modal modalQuestion" data-child-id="{{ $children['id'] }}" tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-xl">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h2 class="font-medium text-base mr-auto">Тест на множинні інтелекти</h2>
                                                                    </div>
                                                                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                                                        <div class="col-span-12 text-center questions-step" style="font-size: 1.5rem; line-height: 2rem;">
                                                                            <span class="currentQuestion">1</span> / <span class="maxQuestion">{{ count($intellectTest['questions']) }}</span>
                                                                        </div>
                                                                        @foreach($intellectTest['questions'] as $question)
                                                                            <div class="col-span-12 question-container" data-slug="{{ $question->intellect->slug }}" data-index="{{ ++$loop->index }}" @if(!$loop->first) style="display: none" @endif>
                                                                                <h2 class="font-medium text-base mr-auto text-center" style="font-size: 1.5rem; line-height: 2rem; margin-top: 20px;min-height: 65px;">
                                                                                    {{ $question->title }}
                                                                                </h2>

                                                                                <div class="flex items-center justify-between" style="margin-top: 50px; margin-bottom: 60px;  justify-content: space-between;">
                                                                                    <div class="col-span-3">
                                                                                        <button type="button" class="btn btn-danger btn-lg click_answer" data-value="0">
                                                                                            Точно ні
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="col-span-3">
                                                                                        <button type="button" class="btn btn-primary btn-lg click_answer" data-value="1">
                                                                                            Скоріше ні
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="col-span-3">
                                                                                        <button type="button" class="btn btn-warning btn-lg click_answer" data-value="2">
                                                                                            Скоріше так
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="col-span-3">
                                                                                        <button type="button" class="btn btn-success btn-lg click_answer" data-value="3">
                                                                                            Точно так
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach

                                                                        <div class="col-span-12 text-center test_result">
                                                                            @foreach($intellectTest['intellects'] as $intellect)
                                                                                <div class="card mb-4" data-slug="{{ $intellect->slug }}">
                                                                                    <div class="card-body">
                                                                                        <div class="container">
                                                                                            <div class="flex items-center justify-between">
                                                                                                <div class="col-span-3" style="width: 25%;">
                                                                                                    <div class="card-title">{{ $intellect->title }}</div>
                                                                                                </div>
                                                                                                <div class="col-span-7" style="width: 50%">
                                                                                                    <div class="text-center" style="font-size: 1rem"><span class="point">0</span> з 15 (<span class="percent">100</span>%)</div>
                                                                                                    <div class="progress h-4 rounded">
                                                                                                        <div class="progress-bar bg-theme-1 rounded"
                                                                                                             style="width: 100%"
                                                                                                             role="progressbar"
                                                                                                             aria-valuenow="17"
                                                                                                             aria-valuemin="0"
                                                                                                             aria-valuemax="100">100%</div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-span-2">
                                                                                                    <button type="button" class="btn btn-xs btn-primary collapse-more" data-slug="{{ $intellect->slug }}">Детальніше</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="mt-4 text-left collapse" data-slug="{{ $intellect->slug }}" style="display: none">
                                                                                        {!! $intellect->description !!}
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach

                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer text-right flex" style="justify-content: space-between;">
                                                                        <button type="button" class="btn btn-primary-soft resetTest">Тест з початку</button>
                                                                        <button type="button" data-dismiss="modal" class="btn btn-elevated-secondary w-24 mr-1">Закрити</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div style="">
                                                            <div style="height: 15px"></div>

                                                            <div class="intro-y box mt-5 ">
                                                                <div align="center" class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                                                    <h3 class="font-medium text-center  mr-5">{{$children['name']}}  - відзнач свої результати за видами інтелекту</h3>

                                                                    <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0"></div>

                                                                </div>
                                                                <br><div class="alert alert-warning-soft show flex items-center mb-2" role="alert">
                                                                    <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> Всі поля обовʼязкові для заповненя
                                                                </div>

                                                                <div class="p-5" id="hoverable-table">
                                                                    <div class="preview" style="display: block; opacity: 1;">
                                                                        {{-- New table view --}}


                                                                        <div class="container">
                                                                            <table class="resp-tab">
                                                                                <thead role="rowgroup">
                                                                                <tr role="row">
                                                                                    <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">
                                                                                        Вид
                                                                                    </th>
                                                                                    <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">
                                                                                        Відсоток ( вносити лише цифру )
                                                                                    </th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr class="hover:bg-gray-200"
                                                                                    role="row">
                                                                                    <td role="cell" class="border"
                                                                                        data-label="Вид">вербально -
                                                                                        лінгвістичний
                                                                                    </td>
                                                                                    <td role="cell" class="border"
                                                                                        data-label="Результат">
                                                                                        <input
                                                                                            onchange="checkNumber(`{{$children['id']}}[mentality][verbal-linguistic]`, 100)"
                                                                                            data-slug="lingvistichnij_{{ $children['id'] }}"
                                                                                            name="{{$children['id']}}[mentality][verbal-linguistic]"
                                                                                            id="{{$children['id']}}[mentality][verbal-linguistic]"
                                                                                            type="number" min="0"
                                                                                            max="100" placeholder="%"
                                                                                            class="form-control input col-span-2"
                                                                                            required>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="hover:bg-gray-200">
                                                                                    <td class="border" data-label="Вид">
                                                                                        логіко - математичний
                                                                                    </td>
                                                                                    <td class="border"
                                                                                        data-label="Результат">
                                                                                        <input
                                                                                            onchange="checkNumber(`{{$children['id']}}[mentality][logical-mathematical]`, 100)"
                                                                                            data-slug="logiko_matematichnij_{{ $children['id'] }}"
                                                                                            name="{{$children['id']}}[mentality][logical-mathematical]"
                                                                                            id="{{$children['id']}}[mentality][logical-mathematical]"
                                                                                            type="number" min="0"
                                                                                            max="100" placeholder="%"
                                                                                            class="form-control input col-span-2"
                                                                                            required>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="hover:bg-gray-200">
                                                                                    <td class="border" data-label="Вид">
                                                                                        музичний
                                                                                    </td>
                                                                                    <td class="border"
                                                                                        data-label="Результат">
                                                                                        <input
                                                                                            onchange="checkNumber(`{{$children['id']}}[mentality][musical]`, 100)"
                                                                                            data-slug="muzichnij_{{ $children['id'] }}"
                                                                                            name="{{$children['id']}}[mentality][musical]"
                                                                                            id="{{$children['id']}}[mentality][musical]"
                                                                                            type="number" min="0"
                                                                                            max="100" placeholder="%"
                                                                                            class="form-control input col-span-2"
                                                                                            required>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="hover:bg-gray-200">
                                                                                    <td class="border" data-label="Вид">
                                                                                        натуралістичний
                                                                                    </td>
                                                                                    <td class="border"
                                                                                        data-label="Результат">
                                                                                        <input
                                                                                            onchange="checkNumber(`{{$children['id']}}[mentality][naturalistic]`, 100)"
                                                                                            data-slug="naturalіstichnij_{{ $children['id'] }}"
                                                                                            name="{{$children['id']}}[mentality][naturalistic]"
                                                                                            id="{{$children['id']}}[mentality][naturalistic]"
                                                                                            type="number" min="0"
                                                                                            max="100" placeholder="%"
                                                                                            class="form-control input col-span-2"
                                                                                            required>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="hover:bg-gray-200">
                                                                                    <td class="border" data-label="Вид">
                                                                                        візуально - просторовий
                                                                                    </td>
                                                                                    <td class="border"
                                                                                        data-label="Результат">
                                                                                        <input
                                                                                            onchange="checkNumber(`{{$children['id']}}[mentality][spatial]`, 100)"
                                                                                            data-slug="vіzualno_prostorovij_{{ $children['id'] }}"
                                                                                            name="{{$children['id']}}[mentality][spatial]"
                                                                                            id="{{$children['id']}}[mentality][spatial]"
                                                                                            type="number" min="0"
                                                                                            max="100" placeholder="%"
                                                                                            class="form-control input col-span-2"
                                                                                            required>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="hover:bg-gray-200">
                                                                                    <td class="border" data-label="Вид">
                                                                                        кінестетичний
                                                                                    </td>
                                                                                    <td class="border"
                                                                                        data-label="Результат">
                                                                                        <input
                                                                                            onchange="checkNumber(`{{$children['id']}}[mentality][kinesthetic]`, 100)"
                                                                                            data-slug="kіnestetichnij_{{ $children['id'] }}"
                                                                                            name="{{$children['id']}}[mentality][kinesthetic]"
                                                                                            id="{{$children['id']}}[mentality][kinesthetic]"
                                                                                            type="number" min="0"
                                                                                            max="100" placeholder="%"
                                                                                            class="form-control input col-span-2"
                                                                                            required>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="hover:bg-gray-200">
                                                                                    <td class="border" data-label="Вид">
                                                                                        міжособистісний
                                                                                    </td>
                                                                                    <td class="border"
                                                                                        data-label="Результат">
                                                                                        <input
                                                                                            onchange="checkNumber(`{{$children['id']}}[mentality][missocial]`, 100)"
                                                                                            data-slug="mіzhosobistіsnij_{{ $children['id'] }}"
                                                                                            name="{{$children['id']}}[mentality][missocial]"
                                                                                            id="{{$children['id']}}[mentality][missocial]"
                                                                                            type="number" min="0"
                                                                                            max="100" placeholder="%"
                                                                                            class="form-control input col-span-2"
                                                                                            required>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="hover:bg-gray-200">
                                                                                    <td class="border" data-label="Вид">
                                                                                        внутрішньо - особистісний
                                                                                    </td>
                                                                                    <td class="border"
                                                                                        data-label="Результат">
                                                                                        <input
                                                                                            onchange="checkNumber(`{{$children['id']}}[mentality][intrapersonal]`, 100)"
                                                                                            data-slug="vnutrіshnoosobistіsnij_{{ $children['id'] }}"
                                                                                            name="{{$children['id']}}[mentality][intrapersonal]"
                                                                                            id="{{$children['id']}}[mentality][intrapersonal]"
                                                                                            type="number" min="0"
                                                                                            max="100" placeholder="%"
                                                                                            class="form-control input col-span-2"
                                                                                            required>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>


                                                                        {{-- End of new table --}}
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div style="height: 15px"></div>
                                                        </div>
                                                        <div style="height: 15px"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> {{--Сильные стороны--}}

                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-3" class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#faq-accordion-collapse-3" aria-expanded="true"
                                                        aria-controls="faq-accordion-collapse-3">
                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                        <label for="input-wizard-1"
                                                               class="text-lg form-label">Навчання</label>
                                                    </div>
                                                </button>
                                            </div>
                                            <div id="faq-accordion-collapse-3" aria-labelledby="faq-accordion-content-3"
                                                 data-bs-parent="#faq-accordion-1" style="display: block;">
                                                <div class="text-gray-700 dark:text-gray-600 leading-relaxed">
                                                    <div style="height: 15px"></div>
                                                    <div align="center"
                                                         class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                        <div>
                                                            <div style="height: 15px"></div>
                                                            <div class="alert alert-secondary show mr-3 training_format_item" role="alert">
                                                            <h3 class="text-center font-medium mr-5">
                                                                <b>Важливий крок - визначення пріоритетів у навчанні. </b> <br>Проаналізуйте успішність за попередній навчальний рік, виставивши оцінки з табеля успішності в колонку “За минулий рік”. <br> Далі, враховуючи результати тесту і Ваше бачення майбутнього, проставте оцінки-цілі в колонку “Ціль”. <br> Це ті оцінки, які дитина хоче та може отримати за результатами навчання протягом семестру. Вони слугують орієнтиром протягом навчання. <br> Рекомендуємо: оцінки-цілі мають бути на 1-2 бали вище попередніх. За потреби, Ви матимете можливість коригувати їх кожного семестру. <br> І, нарешті, оберіть пріоритетність вивчення кожного предмету на поточний семестр. <br> У двох останніх колонках таблиці навпроти кожного предмету оберіть його статус вивчення. <br> В категорію “Знати” варто обирати предмети, які відповідають домінантним інтелектам дитини, важливістю таких знань в майбутньому. <br> Під час навчання з обраних предметів рекомендуємо системно та сумлінно виконувати усі завдання, проєктні роботи, тематичні тести, відвідувати живі уроки та бути на них активним. <br> В категорію “Здати” оберіть ті предмети, які не є першочерговими для Вашої дитини. <br> Для засвоєння матеріалу на базовому рівні буде достатньо виконання завдань та тематичних тестів.
                                                            </h3>
                                                            </div>
                                                            <br>
                                                            <div
                                                                class="alert alert-warning-soft show flex items-center mb-2"
                                                                role="alert">
                                                                <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i>
                                                                <em style="color: red"> Якщо Ви побачите предмети, які
                                                                    Ваш учень не вивчав минулого року, то в колонку "за
                                                                    минулий рік", проставте оцінку ідетнтичну цілі на
                                                                    поточний, або просто залиште поле порожнім. </em>
                                                            </div>
                                                            <div style="height: 15px"></div>
                                                            <div id="{{$children['id']}}-taget-class" class=""></div>
                                                            <div style="height: 15px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> {{--Учеба--}}

                                        <div class="accordion-item">
                                            @if($settingTrainingFormat->value === 'semester_1')
                                                <div align="center" class="two-semester" style="display: none">
                                                    <input checked
                                                            id="training_format_individ_{{$children['id']}}" name="{{$children['id']}}[learning_format_id]"
                                                            value="{{$learning_formats[5]['individ']['id']}}" type="checkbox"
                                                            class="custom-checkbox changeTraining changeTraining_consistent_{{ $children['id'] }}">
                                                </div>
                                            @elseif($settingTrainingFormat->value === 'semester_2')
                                                <div align="center" class="one-semester">
                                                    <label style="margin: 15px; font-size: 15px;" for="input-wizard-1" class="text-m form-label"> <b>Формат навчання </b> <br>
                                                        Ви дізнались унікальні здібності своєї дитини, обрали цілі та визначили серед них пріоритетні. Наступний крок - визначення формату навчання. <br> Це послідовність та графік вивчення предметів, який найкраще підходить Вашій дитині.
                                                        Оберіть формат навчання: послідовний або паралельний. <br> Послідовний формат передбачає виконання завдань з усіх предметів протягом тижня. Термін виконання завдань обмежений датою проходження тематичного тесту. <br> Паралельний формат передбачає вибір послідовності вивчення предметів за методом занурення. Ви маєте обрати один з трьох варіантів послідовностей предметів. <br> Термін виконання завдань обмежений закінченням чверті.</label>

                                                    <div class="training_format">
                                                        <div class="alert alert-secondary show mr-3 training_format_item" role="alert">
                                                            <div class="flex items-center justify-center">
                                                                <div class="font-medium text-lg text-black">Паралельний</div>
                                                            </div>
                                                            <div class="mt-3 text-black">
                                                                Формат в якому учень проходить навчання по всім предметам одночасно, виконуючи завдання поступово.
                                                                <br>Цей формат зазвичай використовують в школах.
                                                                <br>Поступовий рух по рекомендованому розкладу
                                                                <br>
                                                                <br>
                                                                <label class="form-check-label ml-0 sm:ml-2 font-bold" for="training_format_parallel_{{$children['id']}}">Обрати цей формат</label>
                                                                <input checked id="training_format_parallel_{{$children['id']}}" name="{{$children['id']}}[learning_format_id]"
                                                                       value="{{$learning_formats[5]['parallel']['id']}}" type="checkbox"
                                                                       class="custom-checkbox changeTraining changeTraining_parallel_{{ $children['id'] }} changeTraining_{{ $children['id'] }}"
                                                                       onclick="changeTraining({{$children['id']}}, 0, 'changeTraining_parallel_{{ $children['id'] }}')">
                                                                <br>
                                                            </div>
                                                        </div>

                                                        <div class="alert alert-secondary show ml-3 training_format_item" role="alert">
                                                            <div class="flex items-center justify-center">
                                                                <div class="font-medium text-lg text-black">Послідовний</div>
                                                            </div>
                                                            <div class="mt-3 text-black">
                                                                Формат в якому учень може послідовно закривати предмет за предметом.
                                                                <br>Фокусуючиcя на одному предметі і не відволікаючись на інші предмети.
                                                                <br>
                                                                <br>
                                                                <label class="form-check-label ml-0 sm:ml-2 font-bold" for="training_format_consistent_{{$children['id']}}">Обрати цей формат</label>
                                                                <input
                                                                        {{-- {{$children['study'] === 'test' ? 'disabled': ''}} --}}
                                                                        id="training_format_consistent_{{$children['id']}}" name="{{$children['id']}}[learning_format_id]"
                                                                        value="{{$learning_formats[5]['consistent'][0]['id']}}" type="checkbox"
                                                                        class="custom-checkbox changeTraining changeTraining_consistent_{{ $children['id'] }} changeTraining_{{ $children['id'] }}"
                                                                        onclick="changeTraining({{$children['id']}}, 1, 'changeTraining_consistent_{{ $children['id'] }}')">
                                                                <br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div align="center">
                                                        <div style="height: 15px"></div>
                                                        <div style="height: 15px"></div>
                                                    </div>
                                                    <br>
                                                    <div style="display: none" id="variable-{{$children['id']}}">
                                                        <div>
                                                            <div style="height: 15px"></div>
                                                            <div class="fz-24 text-black mb-10">
                                                                Оберіть один з варіантів розкладу, для старту навчаня за послідовною системою
                                                            </div>
                                                            <div class="child-schedule" id="{{$children['id']}}-item-class"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>


                                        <div class="accordion-item container-skills" data-child-id="{{ $children['id'] }}">
                                            <div class="intro-y flex items-center h-30">
                                                <h2 style="width: 100%" class="text-center text-lg font-medium  mr-5">Мультитест </h2>
                                            </div>

                                            <div class="intro-y flex items-center h-20">
                                                <h5 style="width: 100%" class="text-center text-lg font-medium  mr-5">Останній крок - перевірмо наскільки ваша дитина готова до навчання в JAMM24. <br> Результати цього тесту допоможуть нам провести заходи та запропонувати інструменти для формування необхідних навичок швидкого та якісного навчання без стресу Вашої дитини.</h5>
                                            </div>

                                            <div class="flex justify-center mt-5">
                                                <a href="javascript:;" type="button" data-skill-id="{{ $children['id'] }}" class="btn btn-outline-success startSkillTest">Пройти тест на Навчальні/Технічні/Життєві навички</a>
                                            </div>
                                            <div class="hidden" id="container-skill-{{ $children['id'] }}">
                                                <div class="col-span-12 text-center questions-step" style="font-size: 1.5rem; line-height: 2rem;">
                                                    <span class="currentQuestion">1</span> / <span class="maxQuestion">{{ count($skillTest['questions']) }}</span>
                                                </div>

                                                @foreach($skillTest['questions'] as $question)
                                                    <div class="skill_container" data-slug="{{ $question->skill->slug }}" data-index="{{ ++$loop->index }}" @if(!$loop->first) style="display: none" @endif>
                                                        <h2 class="text-center font-medium fz-24 leading-8 mb-10 mt-10">{{ $question['title'] }}</h2>
                                                        <div class="skill_btn flex justify-center">
                                                            <button type="button" class="btn btn-primary btn-lg click_answer_skill ml-2 mr-2" data-value="1">1</button>
                                                            <button type="button" class="btn btn-primary btn-lg click_answer_skill ml-2 mr-2" data-value="2">2</button>
                                                            <button type="button" class="btn btn-primary btn-lg click_answer_skill ml-2 mr-2" data-value="3">3</button>
                                                            <button type="button" class="btn btn-primary btn-lg click_answer_skill ml-2 mr-2" data-value="4">4</button>
                                                            <button type="button" class="btn btn-primary btn-lg click_answer_skill ml-2 mr-2" data-value="5">5</button>
                                                            <button type="button" class="btn btn-primary btn-lg click_answer_skill ml-2 mr-2" data-value="6">6</button>
                                                            <button type="button" class="btn btn-primary btn-lg click_answer_skill ml-2 mr-2" data-value="7">7</button>
                                                            <button type="button" class="btn btn-primary btn-lg click_answer_skill ml-2 mr-2" data-value="8">8</button>
                                                            <button type="button" class="btn btn-primary btn-lg click_answer_skill ml-2 mr-2" data-value="9">9</button>
                                                            <button type="button" class="btn btn-primary btn-lg click_answer_skill ml-2 mr-2" data-value="10">10</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="col-span-12 text-center test_skill_result">
                                                @foreach($skillTest['skills'] as $skill)
                                                    <div class="card mb-4" data-slug="{{ $skill->slug }}">
                                                        <div class="card-body">
                                                            <div class="container">
                                                                <div class="flex items-center justify-between">
                                                                    <div class="col-span-3" style="width: 25%;">
                                                                        <div class="card-title">{{ $skill->title }}</div>
                                                                    </div>
                                                                    <div class="col-span-7" style="width: 50%">
                                                                        <div class="text-center" style="font-size: 1rem"><span class="point">0</span> з 50 (<span class="percent">100</span>%)</div>
                                                                        <div class="progress h-4 rounded">
                                                                            <div class="progress-bar bg-theme-1 rounded"
                                                                                 style="width: 100%"
                                                                                 role="progressbar"
                                                                                 aria-valuenow="17"
                                                                                 aria-valuemin="0"
                                                                                 aria-valuemax="100">100%</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-span-2">
                                                                        <button type="button" class="btn btn-xs btn-primary collapse-more" data-slug="{{ $skill->slug }}">Детальніше</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-4 text-left collapse" data-slug="{{ $skill->slug }}" style="display: none">
                                                            {!! $skill->description !!}
                                                        </div>
                                                    </div>
                                                @endforeach

                                                    <div class="alert alert-warning-soft show flex fz-16 text-left line-h-24 mb-2" role="alert">
                                                        Команда JAMM SCHOOL протягом навчального року запланувала багато заходів, які розвиватимуть необхідні навички учнів для досягнення успіху у навчанні та особистому житті.
                                                        <br>
                                                        Для успішного розвитку навичок та отримання найкращого результату рекомендуємо взяти послугу Супервайзера.
                                                    </div>
                                            </div>

                                            <div class="p-5 mt-5">
                                                <div class="preview" style="display: block; opacity: 1;">
                                                    <div class="container">
                                                        <table class="resp-tab">
                                                            <thead role="rowgroup">
                                                            <tr role="row">
                                                                <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">
                                                                    Навички
                                                                </th>
                                                                <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">
                                                                    Бали
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($skillTest['skills'] as $skill)
                                                                <tr class="hover:bg-gray-200" role="row">
                                                                    <td role="cell" class="border" data-label="Навички">
                                                                        {{ $skill->title }}
                                                                    </td>
                                                                    <td role="cell" class="border" data-label="Результат">
                                                                        <input onchange="checkNumber(`{{ $children['id'] }}[skill][{{ $skill->slug }}]`, 50)"
                                                                               data-slug="{{ $skill->slug }}_{{ $children['id'] }}"
                                                                               id="{{ $children['id'] }}[skill][{{ $skill->slug }}]"
                                                                               name="{{ $children['id'] }}[skill][{{ $skill->slug }}]"
                                                                               type="number" min="0" max="50"
                                                                               placeholder="0"
                                                                               class="form-control input col-span-2"
                                                                               required>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="accordion-item"></div>
                                    </div>
                                </div>

                            @endforeach

                            <div
                                style="margin-top: 20px; margin-bottom:40px; width: 100%; padding-right:0; display:none"
                                class="alert alert-outline-warning alert-dismissible show flex items-center   mb-2">
                                <div сlass="mt-3" align="center" style="width: 100%">
                                    <h1 style="margin:10px; margin-top:20px" class="text-center text-lg font-medium  ">
                                        Якщо ви хочете більше розповісти нам про свою дитину забронюйте зустріч із
                                        засновником JAMM SCHOOL</h1>

                                    {{--<button  type="button" onclick="document.getElementById(`training_format_{{$children['id']}}`).value" style="width: 90%" class="btn btn-success w-24 inline-block mr-1 mb-2">Забронювати</button>--}}

                                    <a target="_blank" href="https://calendly.com/jammschool/zustrich"
                                       style="margin-top:20px; margin-bottom:20px"
                                       class="btn btn-success w-40 inline-block mr-1 mb-2">Забронювати</a>

                                </div>
                                {{-- <div align="center"></div>

                                <div align="center">

                                </div> --}}
                            </div>
                            <div>
                                <label for="from_where" class="form-label">Як ви дізналися про JAMM School</label>
                                <select style="background-color: white" class="form-control w-full" id="from_where"
                                        name="from_where">
                                    <option value="5198">Рекомендаціі друзів</option>
                                    <option value="5208">Від команди JAMM</option>
                                    <option value="5200">Знайшли в Google</option>
                                    <option value="5202">Facebook</option>
                                    <option value="5204">Instagram</option>
                                    <option value="5206">Інше</option>
                                </select>
                            </div>
                            <div align="center"></div>
                            <br>
                            <br>
                            <div class="alert alert-danger show flex items-center mb-2" role="alert">
                                <i data-feather="alert-octagon" class="w-6 h-6 mr-2"></i> Якщо кнопка далі не переводить
                                Вас на наступну сторінку - впевніться, що всі поля заповнено.
                                <br> Також, якщо Ви реєструєте більше одного учня, переконайтеся що анкети заповнені для
                                кожного.
                                <br>( Перемикатися між анкетами через вкладки зверху сторінки )

                            </div>
                            <br>
                            <div align="center">
                                <div class="text-right mt-5">
                                    <button class="btn btn-warning w-24 mr-1 mb-2">Далі</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var mentalityTest = {
            lingvistichnij: 0,
            logiko_matematichnij: 0,
            muzichnij: 0,
            kіnestetichnij: 0,
            vіzualno_prostorovij: 0,
            mіzhosobistіsnij: 0,
            vnutrіshnoosobistіsnij: 0,
            naturalіstichnij: 0,
        };

        $(document).ready(function() {
            $('.collapse-more').on('click', function (e) {
                e.preventDefault();
                var slug = $(this).data('slug');
                $(`.collapse[data-slug=${slug}]`).slideToggle();
            })

            $('.click_answer').on('click', function (e) {
                e.preventDefault();
                var containerModal = $(this).parents('.modalQuestion');
                var childId = containerModal.data('child-id');
                let maxQuestion = parseInt(containerModal.find('.maxQuestion').text());
                var value = parseInt($(this).data('value'));
                var parent = $(this).parents('.question-container');
                var intellect = parent.data('slug');
                var index = parent.data('index');
                let nextQuestion = ++index;
                containerModal.find('.currentQuestion').text(nextQuestion);

                mentalityTest[intellect] += value;

                parent.hide();
                if (nextQuestion <= maxQuestion) {
                    containerModal.find(`.question-container[data-index=${nextQuestion}]`).show();
                } else {
                    containerModal.find('.test_result').show();
                    containerModal.find('.questions-step').hide();
                    // Calculation
                    $.each(mentalityTest, function(key, value) {
                        var resultContainer = containerModal.find(`.card[data-slug=${key}]`);
                        var percent = (value * 100 / 15).toFixed(0);
                        resultContainer.find('.point').text(value);
                        resultContainer.find('.percent').text(percent);
                        resultContainer.find('.progress-bar').text(percent + '%').css('width', percent + '%');
                        $(`input.input[data-slug=${key}_${childId}]`).val(percent);
                    });
                }

            });

            $('.resetTest').on('click', function (e) {
                e.preventDefault();

                mentalityTest = {
                    lingvistichnij: 0,
                    logiko_matematichnij: 0,
                    muzichnij: 0,
                    kіnestetichnij: 0,
                    vіzualno_prostorovij: 0,
                    mіzhosobistіsnij: 0,
                    vnutrіshnoosobistіsnij: 0,
                    naturalіstichnij: 0,
                };

                let containerModal = $(this).parents('.modalQuestion');
                let firstQuestion = containerModal.find('.question-container:first');
                containerModal.find('.question-container').hide();
                containerModal.find('.test_result').hide();
                firstQuestion.show();
                containerModal.find('.currentQuestion').text(firstQuestion.data('index'));
                containerModal.find('.questions-step').show();
            });
        });

        const resetTest = (id) => {
            mentalityTest = {
                lingvistichnij: 0,
                logiko_matematichnij: 0,
                muzichnij: 0,
                kіnestetichnij: 0,
                vіzualno_prostorovij: 0,
                mіzhosobistіsnij: 0,
                vnutrіshnoosobistіsnij: 0,
                naturalіstichnij: 0,
            };

            let containerModal = $('#question_' + id);
            let firstQuestion = containerModal.find('.question-container:first');
            containerModal.find('.question-container').hide();
            containerModal.find('.test_result').hide();
            firstQuestion.show();
            containerModal.find('.currentQuestion').text(firstQuestion.data('index'));
            containerModal.find('.questions-step').show();
        }
    </script>

    <script>
        let changeTraining = (id, show, changeClass) => {
            $(`.changeTraining_${id}`).prop('checked', false);
            $(`.${changeClass}`).prop('checked', true);

            if (show) {
               $(`#variable-${id}`).show();
           } else {
               $(`#variable-${id}`).hide();
           }
        }
    </script>

    <script>
        $(document).ready(function() {

            var skillTest = {
                study: 0,
                technical: 0,
                life: 0,
            };

            $(document).on('click', '.startSkillTest', function (e) {
                e.preventDefault();

                let id = $(this).data('skill-id')

                $(`#container-skill-${id}`).show();
                $(this).hide();

                 skillTest = {
                    study: 0,
                    technical: 0,
                    life: 0,
                };
            })

            $('.click_answer_skill').on('click', function (e) {
                e.preventDefault();

                var containerSkills = $(this).parents('.container-skills');
                var childId = containerSkills.data('child-id');
                let maxQuestion = parseInt(containerSkills.find('.maxQuestion').text());
                var value = parseInt($(this).data('value'));
                var parent = $(this).parents('.skill_container');
                var skill = parent.data('slug');
                var index = parent.data('index');
                let nextQuestion = ++index;
                containerSkills.find('.currentQuestion').text(nextQuestion);

                skillTest[skill] += value;

                parent.hide();
                if (nextQuestion <= maxQuestion) {
                    containerSkills.find(`.skill_container[data-index=${nextQuestion}]`).show();
                } else {
                    containerSkills.find('.test_skill_result').show();
                    containerSkills.find('.questions-step').hide();
                    // Calculation
                    $.each(skillTest, function(key, value) {
                        var resultContainer = containerSkills.find(`.card[data-slug=${key}]`);
                        var percent = (value * 100 / 50).toFixed(0);
                        resultContainer.find('.point').text(value);
                        resultContainer.find('.percent').text(percent);
                        resultContainer.find('.progress-bar').text(percent + '%').css('width', percent + '%');
                        $(`input.input[data-slug=${key}_${childId}]`).val(value);
                    });
                }

            });
        });

    </script>

@endsection
@include('constant')
@include('js_function')

@push('footer_scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>

    <script>
        const userPhones = document.querySelectorAll('.intl-phone');

        if (userPhones.length > 0) {
            userPhones.forEach(function (elem) {
                var input = elem
                var userId = elem.getAttribute('data-user-id');
                var errorMsg = document.querySelector(`.error-msg[data-user-id="${userId}"]`);

                const errorMap = ["Невірний номер", "Невірний код країни", "Занадто короткий", "Занадто довгий", "Невірний номер"];

                const iti = window.intlTelInput(input, {
                    initialCountry: "UA",
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                });

                const reset = () => {
                    input.classList.remove("phone-error");
                    errorMsg.innerHTML = "";
                    errorMsg.classList.add("hide");
                };

                const getPhoneNumberWithCountryCode = () => {
                    return iti.getNumber();
                };

                const validateNumber = () => {
                    reset();

                    var userPhoneInput = document.querySelector(`.user_phone[data-user-id="${userId}"]`);
                    userPhoneInput.value = getPhoneNumberWithCountryCode();

                    if (input.value.trim()) {
                        if (!iti.isValidNumber()) {
                            input.classList.add("phone-error");
                            const errorCode = iti.getValidationError();

                            if (errorMap[errorCode] !== undefined) {
                                errorMsg.innerHTML = errorMap[errorCode];
                                errorMsg.classList.remove("hide");
                            }
                        }
                    }
                }

                input.addEventListener('keyup', validateNumber);
                input.addEventListener('change', validateNumber);
                window.addEventListener("load", function() {
                    setTimeout(function (){
                        validateNumber();
                    }, 1000)
                });
            });
        }
    </script>
@endpush

<script>
    function tabForm(id) {
        @foreach($item as $value)
            document.getElementById('children_{{$value['id']}}').style = 'display: none;';
        @endforeach
        document.getElementById(`children_${id}`).style = '';
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
    document.addEventListener('DOMContentLoaded', function() {
        for (let id in document.getElementsByClassName('button-apply')) {
            setInterval(function () {
                document.getElementsByClassName('button-apply')[id].innerHTML = 'Застосувати';
                document.getElementsByClassName('button-apply')[id].style = 'height:auto; width:auto';
            }, 1);
        }
        for (let id in document.getElementsByClassName('button-cancel')) {
            setInterval(function () {
                document.getElementsByClassName('button-cancel')[id].innerHTML = 'Скасувати';
                document.getElementsByClassName('button-cancel')[id].style = 'height:auto; width:auto';
            }, 1);
        }
    }, false);

</script>
