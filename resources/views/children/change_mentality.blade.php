@extends('../layout/' . $layout)

@section('subhead')
    <title>Зміна цілей</title>
@endsection

@section('subcontent')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">
                    <div class="intro-y box p-5">
                        <form method="post" action="{{route('save-mentality', ['child' => $children['id']])}}" enctype="multipart/form-data">
                            @csrf
                            <div class="accordion-item">
                                <div id="faq-accordion-content-2" class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-2" aria-expanded="true" aria-controls="faq-accordion-collapse-2">
                                        <div class="intro-y col-span-12 sm:col-span-6">
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
                                                                                    value="{{ $children['mentality']['verbal-linguistic'] }}"
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
                                                                                    value="{{ $children['mentality']['logical-mathematical'] }}"
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
                                                                                    value="{{ $children['mentality']['musical'] }}"
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
                                                                                    value="{{ $children['mentality']['naturalistic'] }}"
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
                                                                                    value="{{ $children['mentality']['spatial'] }}"
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
                                                                                    value="{{ $children['mentality']['kinesthetic'] }}"
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
                                                                                    value="{{ $children['mentality']['missocial'] }}"
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
                                                                                    value="{{ $children['mentality']['intrapersonal'] }}"
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
                                <div align="center">
                                    <div class="text-right mt-5">
                                        <button class="btn btn-warning w-24 mr-1 mb-2">Зберегти</button>
                                    </div>
                                </div>
                            </div> {{--Сильные стороны--}}
                        </form>
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

@endsection
@include('constant')
@include('js_function')

<style>
    .resp-tab {
        border-radius: 5px;
        font-weight: normal;
        border: none;
        border-collapse: collapse;
        width: 100%;
        max-width: 100%;
    }

    .resp-tab th, .resp-tab td {
        padding: 10px 20px;
        font-size: 13px;
        border: none;
        font-family: Verdana, sans-serif;
        border: 1px solid #FBCA36;
        vertical-align: top;
    }

    .resp-tab th {
        color: white;
        background: #FBCA36;
        font-weight: bold;
        border: 1px solid #FBCA36;

        text-transform: uppercase;
        text-align: center;
    }

    .resp-tab tr:nth-child(even) {
        background: #FEF9E7;
    }

    .resp-tab td span {
        background: #FBCA36;
        color: white;
        display: none;
        font-size: 11px;
        font-weight: bold;
        font-family: Verdana, sans-serif;
        text-transform: uppercase;
        padding: 5px 10px;
        position: absolute;
        top: 0;
        left: 0;
    }

    @media (max-width: 768px) {
        .resp-tab thead {
            display: none;
        }

        table td .input {
            width: 50%;
            height: 30px;
        }

        table td::before {
            content: attr(data-label);
            font-weight: bold;
            margin-right: 20px;

        }

        .resp-tab tr {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .resp-tab td {
            margin: 0 -1px -1px 0;
            padding-top: 35px;
            position: relative;
            width: 50%;
        }

        .resp-tab td span {
            display: block;
        }
    }

    @media (max-width: 480px) {
        .resp-tab td {
            width: 100%;
        }
    }
</style>

