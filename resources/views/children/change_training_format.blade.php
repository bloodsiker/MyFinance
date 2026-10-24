@extends('../layout/' . $layout)

@section('subhead')
    <title>Зміна цілей</title>
@endsection

@section('subcontent')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">

                    <input type="hidden" name="{{$children['id']}}[class]" id="{{$children['id']}}[class]" value="{{ $children['class'] }}">

                    <form method="post" action="{{route('save-raining-format', ['child' => $children['id']])}}" enctype="multipart/form-data">
                        @csrf
                        <div class="intro-y box p-5">

                            <div class="accordion-item">
                                <div align="center">
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
                                                <input id="training_format_parallel_{{$children['id']}}" name="{{$children['id']}}[training_format]"
                                                       value="parallel" type="checkbox"
                                                       @if($children['training_format'] === 'parallel') checked @endif
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
                                                        id="training_format_consistent_{{$children['id']}}" name="{{$children['id']}}[training_format]"
                                                        value="consistent" type="checkbox"
                                                        @if($children['training_format'] === 'consistent') checked @endif
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
                                    <div style="display: {{ $children['training_format'] === 'consistent' ? 'block' : 'none' }}" id="variable-{{$children['id']}}">
                                        <div>
                                            <div style="height: 15px"></div>
                                            <div class="fz-24 text-black mb-10">
                                                Оберіть один з варіантів розкладу, для старту навчаня за послідовною системою
                                            </div>
                                            <div class="child-schedule" id="{{$children['id']}}-item-class"></div>
                                        </div>
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

@endsection
@include('constant')
@include('js_function')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.onload = setClassItems(`{{$children['id']}}`, `{{$children['name']}}`);
    }, false);
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

