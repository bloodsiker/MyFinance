@extends('../layout/' . $layout)

@section('subhead')
    <title>Акаунт</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">
                    <div align="center">
                        <div class="intro-y box mt-5">
                            <form action="{{route('children.create')}}" method="post" enctype="multipart/form-data">
                                <div id="faq-accordion-20" class="accordion p-4">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ request()->get('user_id') }}">

                                    <div align="left">Ім'я</div>
                                    <div>
                                        <input required name="name" id="name" onchange="checkName(`name`)" type="text" class="form-control" value="" placeholder="ім'я" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}">
                                    </div>
                                    <div style="height: 10px"></div>
                                    <div align="left">Прізвище</div>
                                    <div>
                                        <input required name="last_name" id="last_name" onchange="checkName(`last_name`)" type="text" class="form-control" value="" placeholder="Прізвище" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}">
                                    </div>
                                    <div style="height: 10px"></div>
                                    <div align="left">По батькові</div>
                                    <div>
                                        <input required name="middle_name" id="middle_name" onchange="checkName(`middle_name`)" type="text" class="form-control" value="" placeholder="По батькові" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}">
                                    </div>

                                    <div style="height: 10px"></div>
                                    <div align="left">Дата народження</div>
                                    <div>
                                        <input required name="birth" id="birth" class="form-control" placeholder="дд.мм.рр">
                                    </div>

                                    <div style="height: 15px"></div>
                                    <label for="file">
                                        <div class="dropzone">
                                            <div class="fallback">
                                                <input onchange="test(`file`)" accept="image/*" required style="display: block" id="file" name="photo" type="file">
                                            </div>
                                            <div class="dz-message" data-dz-message>
                                                <div class="text-lg font-medium">Натисніть, щоб завантажити фото дитини.  <br><em>( Обовʼязкове для завантаження )</em></div>
                                                <div class="flex w-100 justify-start" style="max-height: 150px;height: 150px">
                                                    <div class="flex">
                                                        <img id="file-file" data-dz-thumbnail="" style="border-radius: 20px; width:auto;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <div style="height: 10px"></div>
                                    <div align="left">Телефон</div>
                                    <div>
{{--                                        <input name="phone" id="phone" onchange="checkPhone('phone')" title="Номер телефону." pattern="[0-9+]{9,13}" type="tel" class="form-control" value="" placeholder="Телефон">--}}
                                        <input id="phone" required class="intl-phone form-control" name="virtual_phone" value="" type="tel" placeholder="Телефон">
                                        <input type="hidden" class="user_phone" name="phone">
                                        <div class="text-left">
                                            <span class="error-msg"></span>
                                        </div>
                                    </div>
                                    <div style="height: 10px"></div>
                                    <div align="left">Електронна пошта</div>
                                    <div>
                                        <input required name="email" id="email" onchange="checkEmail('email')" type="text" class="form-control" value="" placeholder="Електронна пошта">
                                    </div>

                                    <div style="height: 10px"></div>
                                    <div align="left" class="flex items-center">
                                        <input onclick="readonly(`zoom-email`)" name="zoom-email-check" id="zoom-email-check" data-target="#on-click-tooltip" class="show-code form-check-switch mr-3 ml-3" type="checkbox">
                                        <label for="zoom-email-check"> я буду використовувати цей email для Google Meet</label>
                                        <input style="display: none" id="previous-zoom-email-check">
                                    </div>
                                    <br>

                                    <div style="height: 10px"></div>
                                    <div align="left">Email для Google Meet</div>
                                    <div>
                                        <input name="zoom_email" id="zoom-email" onchange="checkEmail('zoom-email')" required type="text" class="form-control" value="" placeholder="Email для Google Meet">
                                    </div>

                                    <div style="height: 10px"></div>
                                    <div align="left">Клас</div>
                                    <div>
                                        <select onchange="setClassForOneChildItems()" class="form-control w-full" name="class" id="class">
                                            @for($i = 5; $i <= 11; $i++)
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                        </select>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                window.onload = setClassForOneChildItems();
                                            }, false);
                                        </script>
                                    </div>
                                </div>

                                <div style="height: 20px"></div>
                                <hr>
                                <div style="height: 20px"></div>

                                <div class="accordion-item p-4">
                                    <div id="faq-accordion-content-2" class="accordion-header text-left">
                                        <h2 class="fz-18 font-medium color-blue">Сильні сторони</h2>
                                    </div>
                                </div>

                                <div id="faq-accordion-collapse-2" class="p-4"  aria-labelledby="faq-accordion-content-2" data-bs-parent="#faq-accordion-1" style="display:block">
                                    <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                        <div style="height: 15px"></div>
                                        <div align="center">
                                            <div style="height: 15px"></div>
                                            <div class="alert alert-secondary show mr-3 training_format_item" role="alert">
                                                <h3 style="width: 100%" class="text-center font-medium  mr-5"><b>Наша школа базується на множинних інтелектів </b> <br>Керуючись теорією множинних інтелектів Г.Гарднера, ми розробили опитування. Воно дозволяє визначити схильності дитини та унікальну комбінацію домінантних інтелектів. <br> Результати тесту допоможуть визначити сфери діяльності, до яких у Вашої дитини є найбільший хист на даному етапі її розвитку. <br> Це є основою ранньої профорієнтації та критерієм у виборі навчальних предметів для системного та глибинного опанування. <br> Даний тест має пройти Ваша дитина. </h3>
                                            </div>
                                            <div style="height: 15px"></div>

                                            <a href="javascript:;" onclick="resetTest()" data-toggle="modal" data-target="#question" type="button" class="btn btn-outline-success">Пройти тест на множинні інтелекти</a>


                                            <div id="question" class="modal modalQuestion" tabindex="-1" aria-hidden="true">
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
                                                        <h3 class="font-medium text-center  mr-5">Відзнач свої результати за видами інтелекту</h3>

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
                                                                                    onchange="checkNumber(`mentality[verbal-linguistic]`, 100)"
                                                                                    data-slug="lingvistichnij"
                                                                                    name="mentality[verbal-linguistic]"
                                                                                    id="mentality[verbal-linguistic]"
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
                                                                                    onchange="checkNumber(`mentality[logical-mathematical]`, 100)"
                                                                                    data-slug="logiko_matematichnij"
                                                                                    name="mentality[logical-mathematical]"
                                                                                    id="mentality[logical-mathematical]"
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
                                                                                    onchange="checkNumber(`mentality[musical]`, 100)"
                                                                                    data-slug="muzichnij"
                                                                                    name="mentality[musical]"
                                                                                    id="mentality[musical]"
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
                                                                                    onchange="checkNumber(`mentality[naturalistic]`, 100)"
                                                                                    data-slug="naturalіstichnij"
                                                                                    name="mentality[naturalistic]"
                                                                                    id="mentality[naturalistic]"
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
                                                                                    onchange="checkNumber(`[mentality][spatial]`, 100)"
                                                                                    data-slug="vіzualno_prostorovij"
                                                                                    name="mentality[spatial]"
                                                                                    id="mentality[spatial]"
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
                                                                                    onchange="checkNumber(`mentality[kinesthetic]`, 100)"
                                                                                    data-slug="kіnestetichnij"
                                                                                    name="mentality[kinesthetic]"
                                                                                    id="mentality[kinesthetic]"
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
                                                                                    onchange="checkNumber(`mentality[missocial]`, 100)"
                                                                                    data-slug="mіzhosobistіsnij"
                                                                                    name="mentality[missocial]"
                                                                                    id="mentality[missocial]"
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
                                                                                    onchange="checkNumber(`mentality[intrapersonal]`, 100)"
                                                                                    data-slug="vnutrіshnoosobistіsnij"
                                                                                    name="mentality[intrapersonal]"
                                                                                    id="mentality[intrapersonal]"
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

                                <div style="height: 20px"></div>
                                <hr>
                                <div style="height: 20px"></div>

                                <div class="accordion-item p-4">
                                    <div id="faq-accordion-content-3" class="accordion-header text-left">
                                        <h2 class="fz-18 font-medium color-blue">Навчання</h2>
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
                                                    <div class="alert alert-warning-soft show flex items-center mb-2" role="alert">
                                                        <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i>
                                                        <em style="color: red"> Якщо Ви побачите предмети, які
                                                            Ваш учень не вивчав минулого року, то в колонку "за
                                                            минулий рік", проставте оцінку ідетнтичну цілі на
                                                            поточний, або просто залиште поле порожнім. </em>
                                                    </div>
                                                    <div style="height: 15px"></div>
                                                    <div id="taget-class" class=""></div>
                                                    <div style="height: 15px"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> {{--Учеба--}}

                                <div style="height: 20px"></div>
                                <hr>
                                <div style="height: 20px"></div>

                                <div class="accordion-item p-4">
                                    @if($settingTrainingFormat->value === 'semester_1')
                                        <div align="center" class="two-semester" style="display: none">
                                            <input checked id="training_format_individ" name="learning_format_id"
                                                    value="{{$learning_formats[5]['individ']['id']}}" type="checkbox"
                                                    class="custom-checkbox changeTraining changeTraining_consistent">
                                        </div>
                                    @elseif($settingTrainingFormat->value === 'semester_2')
                                        <div align="center" class="one-semester">
                                            <label style="margin: 15px; font-size: 15px;" for="input-wizard-1" class="text-m form-label"> <b>Формат навчання </b> <br>
                                                Ви дізнались унікальні здібності своєї дитини, обрали цілі та визначили серед них пріоритетні. Наступний крок - визначення формату навчання. <br> Це послідовність та графік вивчення предметів, який найкраще підходить Вашій дитині.
                                                Оберіть формат навчання: послідовний або паралельний. <br> Послідовний формат передбачає виконання завдань з усіх предметів протягом тижня. Термін виконання завдань обмежений датою проходження тематичного тесту. <br> Паралельний формат передбачає вибір послідовності вивчення предметів за методом занурення. Ви маєте обрати один з трьох варіантів послідовностей предметів. <br> Термін виконання завдань обмежений закінченням чверті.</label>

                                            <br>
                                            <br>
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
                                                        <label class="form-check-label ml-0 sm:ml-2 font-bold" for="training_format_parallel">Обрати цей формат</label>
                                                        <input checked id="training_format_parallel" name="learning_format_id"
                                                               value="{{$learning_formats[5]['parallel']['id']}}" type="checkbox"
                                                               class="custom-checkbox changeTraining changeTraining_parallel changeTraining"
                                                               onclick="changeTraining(0, 'changeTraining_parallel')">
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
                                                        <label class="form-check-label ml-0 sm:ml-2 font-bold" for="training_format_consistent">Обрати цей формат</label>
                                                        <input
                                                                {{-- {{$children['study'] === 'test' ? 'disabled': ''}} --}}
                                                                id="training_format_consistent" name="learning_format_id"
                                                                value="{{$learning_formats[5]['consistent'][0]['id']}}" type="checkbox"
                                                                class="custom-checkbox changeTraining changeTraining_consistent changeTraining"
                                                                onclick="changeTraining(1, 'changeTraining_consistent')">
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div align="center">
                                                <div style="height: 15px"></div>
                                                <div style="height: 15px"></div>
                                            </div>
                                            <br>
                                            <div style="display: none" id="variable">
                                                <div>
                                                    <div style="height: 15px"></div>
                                                    <div class="fz-24 text-black mb-10">
                                                        Оберіть один з варіантів розкладу, для старту навчаня за послідовною системою
                                                    </div>
                                                    <div class="child-schedule" id="item-class"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="accordion-item p-5 container-skills">
                                    <div class="intro-y flex items-center h-30">
                                        <h2 style="width: 100%" class="text-center text-lg font-medium  mr-5">Мультитест </h2>
                                    </div>

                                    <div class="intro-y flex items-center h-20">
                                        <h5 style="width: 100%" class="text-center text-lg font-medium  mr-5">Останній крок - перевірмо наскільки ваша дитина готова до навчання в JAMM24. <br> Результати цього тесту допоможуть нам провести заходи та запропонувати інструменти для формування необхідних навичок швидкого та якісного навчання без стресу Вашої дитини.</h5>
                                    </div>

                                    <div class="flex justify-center mt-5">
                                        <a href="javascript:;" type="button" data-skill-id="0" class="btn btn-outline-success startSkillTest">Пройти тест на Навчальні/Технічні/Життєві навички</a>
                                    </div>
                                    <div class="hidden" id="container-skill-0">
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
                                                                <input onchange="checkNumber(`skill[{{ $skill->slug }}]`, 50)"
                                                                       data-slug="{{ $skill->slug }}"
                                                                       id="skill[{{ $skill->slug }}]"
                                                                       name="skill[{{ $skill->slug }}]"
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

                                <button class="btn btn-warning w-24 mr-1 mb-2">Зберегти</button>
                                <div style="height: 20px"></div>
                            </form>

                        </div>

                    </div>
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
                        $(`input.input[data-slug=${key}`).val(percent);
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

        const resetTest = () => {
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

            let containerModal = $('#question');
            let firstQuestion = containerModal.find('.question-container:first');
            containerModal.find('.question-container').hide();
            containerModal.find('.test_result').hide();
            firstQuestion.show();
            containerModal.find('.currentQuestion').text(firstQuestion.data('index'));
            containerModal.find('.questions-step').show();
        }


        let changeTraining = (show, changeClass) => {
            $(`.changeTraining`).prop('checked', false);
            $(`.${changeClass}`).prop('checked', true);

            if (show) {
                $(`#variable`).show();
            } else {
                $(`#variable`).hide();
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
                        $(`input.input[data-slug=${key}`).val(value);
                    });
                }

            });
        });

    </script>

@endsection

@include('constant')
@include('js_function')

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

    function readonly(id){
        if (document.getElementById(id).hasAttribute('readonly') === false){
            document.getElementById(id).setAttribute('readonly', 'readonly');
            document.getElementById('previous-'+id).value = document.getElementById(id).value;
            document.getElementById(id).value = '';
        } else {
            document.getElementById(id).removeAttribute('readonly');
            document.getElementById(id).value = document.getElementById('previous-'+id).value;
        }
    }
</script>

@push('footer_scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>

    <script>
        const input = document.querySelector(".intl-phone");
        const errorMsg = document.querySelector(".error-msg");

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

            const userPhoneInput = document.querySelector('.user_phone');
            userPhoneInput.value = getPhoneNumberWithCountryCode();
            // console.log(iti.getSelectedCountryData());

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
    </script>
@endpush
