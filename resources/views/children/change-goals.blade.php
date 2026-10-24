@extends('../layout/' . $layout)

@section('subhead')
    <title>Зміна цілей</title>
@endsection

@section('subcontent')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">

                    <div class="intro-y flex items-center h-10"></div>
                    <div class="intro-y flex items-center h-30">
                        <h2 style="width: 100%" class="text-center text-lg font-medium  mr-5">Круто, майже досягли цілі.
                            Для створення особистого кабінету на навчальній платформі потрібна додаткова
                            інформація.</h2>
                    </div>
                    <br>
                    <div class="alert alert-warning show flex items-center mb-2" role="alert">
                        <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> Всі поля обовʼязкові для заповненя
                    </div>

                    <div class="intro-y flex items-center h-10"></div>

                    <form method="post" action="{{route('save-goals', ['child' => $child['id']])}}" enctype="multipart/form-data">

                        <div class="intro-y box p-5">

                            <div id="faq-accordion-1" class="accordion p-5">

                                <div class="accordion-item">
                                    <div id="faq-accordion-collapse-3" aria-labelledby="faq-accordion-content-3"
                                         data-bs-parent="#faq-accordion-1" style="display: block;">
                                        <div class="text-gray-700 dark:text-gray-600 leading-relaxed">
                                            <div style="height: 15px"></div>
                                            <div align="center"
                                                 class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                <div>
                                                    <div style="height: 15px"></div>
                                                    <h3 class="text-center font-medium mr-5">
                                                        Для більш успішного навчання в школі ми пропонуємо вам поставити
                                                        цілі по кожному предмету
                                                    </h3>
                                                    <br>
                                                    <div class="alert alert-warning-soft show flex items-center mb-2"
                                                         role="alert">
                                                        <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i>
                                                        <em style="color: red"> Якщо Ви побачите предмети, які Ваш учень
                                                            не вивчав минулого року, то в колонку "за минулий рік",
                                                            проставте оцінку ідетнтичну цілі на поточний, або просто
                                                            залиште поле порожнім. </em>
                                                    </div>
                                                    <div style="height: 15px"></div>
                                                    <div id="{{$child['id']}}-taget-classs" class="">


                                                        <div class="intro-y box mt-5 ">

                                                            <div class="p-5" id="hoverable-table">
                                                                <div class="preview" style="display: block; opacity: 1;">

                                                                    <div class="container">
                                                                        <table class = "resp-tab">
                                                                            <thead>
                                                                            <tr>
                                                                                <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">предмет</th>
                                                                                <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">за минулий рік</th>
                                                                                <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">ціль</th>
                                                                                <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">знати</th>
                                                                                <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">здати</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            @foreach($child['lesson'] as $lesson => $goals)
                                                                                @php
                                                                                    if (empty($goals['radio'])) {
                                                                                        $goals['radio'] = 'hand';
                                                                                    }
                                                                                @endphp
                                                                                <tr class="hover:bg-gray-200">
                                                                                    <td data-label="Предмет" class="border">{{$goals['name']}}</td>
                                                                                    <td data-label="за минулий рік" class="border">
                                                                                        <input
                                                                                                value="{{$goals['current']}}"
                                                                                                data-label="За минулий рік" type="number" class="form-control input col-span-2"
                                                                                                min="0" max="12" style="height:25px"
                                                                                                name="{{$child['id']}}[lesson][{{$lesson}}][current]"
                                                                                                id="{{$child['id']}}[lesson][{{$lesson}}][current]"
                                                                                                onchange="checkNumber('{{$child['id']}}[lesson][{{$lesson}}][current]', 12);"
                                                                                        />
                                                                                    </td>
                                                                                    <td data-label="ціль" class="border">
                                                                                        <input
                                                                                                type="number"
                                                                                                value="{{$goals['target']}}"
                                                                                                class="form-control input col-span-2" required min="0" max="12" style="height:25px"
                                                                                                name="{{$child['id']}}[lesson][{{$lesson}}][target]"
                                                                                                id="{{$child['id']}}[lesson][{{$lesson}}][target]"
                                                                                                onchange="checkNumber('{{$child['id']}}[lesson][{{$lesson}}][target]', 12);"
                                                                                        />
                                                                                    </td>
                                                                                    <td data-label="знати" class="border" >
                                                                                        <div align="center">
                                                                                            <input
                                                                                                    {{$goals['radio'] === 'know' ? 'checked' : ''}}
                                                                                                    type="radio" value="know" class="center"
                                                                                                    name="{{$child['id']}}[lesson][{{$lesson}}][radio]"
                                                                                                    id="{{$child['id']}}[lesson][{{$lesson}}][radio]"
                                                                                                    onchange="maxRating('{{$child['id']}}[lesson][{{$lesson}}][target]', 12);"
                                                                                            />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td data-label="здати" class="border" >
                                                                                        <div align="center">
                                                                                            <input
                                                                                                    {{$goals['radio'] === 'hand' ? 'checked' : ''}}
                                                                                                    type="radio" value="hand" class="center"
                                                                                                    name="{{$child['id']}}[lesson][{{$lesson}}][radio]"
                                                                                                    id="{{$child['id']}}[lesson][{{$lesson}}][radio]"
                                                                                                    onchange="maxRating('{{$child['id']}}[lesson][{{$lesson}}][target]', 12);"
                                                                                            />
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>

                                                                                <script>

                                                                                    @if($goals['radio'] === 'know')
                                                                                    maxRating('{{$child['id']}}[lesson][{{$lesson}}][target]', 12);
                                                                                    @else
                                                                                    maxRating('{{$child['id']}}[lesson][{{$lesson}}][target]', 12);
                                                                                    @endif

                                                                                    @if(in_array($lesson, [
                                                                                        'mistectvo',
                                                                                        'fіzichnakultura',
                                                                                        'trudovenavchannya',
                                                                                        'osnovizdorovya',
                                                                                        'osnovipravoznavstva',
                                                                                        'pravoznavstvo',
                                                                                        'zahistvіtchizni',
                                                                                        'gromadyanskaosvіta'
                                                                                    ]))
                                                                                        document.getElementById(`{{$child['id']}}[lesson][{{$lesson}}][radio]`).disabled = true;
                                                                                    @endif
                                                                                </script>

                                                                            @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <div style="height: 15px"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> {{--Учеба--}}

                                <div class="accordion-item"></div>

                            </div>
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

