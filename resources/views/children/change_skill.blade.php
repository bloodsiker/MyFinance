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
                        <form method="post" action="{{route('save-skill', ['child' => $children['id']])}}" enctype="multipart/form-data">
                            @csrf
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
                                                                   value="{{ $children['skill'] ? $children['skill'][$skill->slug] : 0 }}"
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
                                <div align="center">
                                    <div class="text-right mt-5">
                                        <button class="btn btn-warning w-24 mr-1 mb-2">Зберегти</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('.collapse-more').on('click', function (e) {
                e.preventDefault();
                var slug = $(this).data('slug');
                $(`.collapse[data-slug=${slug}]`).slideToggle();
            })

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
                        console.log($(`input.input[data-slug=${key}_${childId}]`).val());
                    });
                }

            });
        });

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

