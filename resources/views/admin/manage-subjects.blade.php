@extends('../layout/' . $layout)

@section('subhead')
    <title>Налаштування учбових предметів</title>
@endsection

@section('subcontent')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">

                    <div class="intro-y flex items-center h-10"></div>
                    <div class="intro-y flex items-center h-30">
                        <h2 style="width: 100%" class="text-center text-lg font-medium  mr-5">Налаштування учбових предметів</h2>
                    </div>
                    <br>
                    {{--<div class="alert alert-warning show flex items-center mb-2" role="alert">
                        <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> Всі поля обовʼязкові для заповненя
                    </div>--}}

                    <div class="intro-y flex items-center h-10"></div>

                    <div class="intro-y box p-5">

                    <form method="post" action="{{route('admin-save-subjects')}}" enctype="multipart/form-data">
                        <div id="faq-accordion-1" class="accordion p-5">
                            <div class="accordion-item">
                                <div id="faq-accordion-collapse-3" aria-labelledby="faq-accordion-content-3"
                                     data-bs-parent="#faq-accordion-1" style="display: block;">
                                    <div class="text-gray-700 dark:text-gray-600 leading-relaxed">
                                        <div style="height: 15px"></div>
                                        <div align="center"
                                             class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                            <div>
                                                <div id="subjects-list" class="">

                                                    <div class="intro-y box mt-5 ">

                                                        <div class="p-5" id="hoverable-table">
                                                            <div class="preview" style="display: block; opacity: 1;">
                                                                <div class="container">
                                                                    @if(!empty($subjects))
                                                                    <table class = "resp-tab">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Назва</th>
                                                                            <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Транслітерація</th>
                                                                            <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Шорткод</th>
                                                                            <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Значення</th>
                                                                            <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap" colspan="2">5кл/hand</th>
                                                                            <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap" colspan="2">6кл/hand</th>
                                                                            <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap" colspan="2">7кл/hand</th>
                                                                            <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap" colspan="2">8кл/hand</th>
                                                                            <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap" colspan="2">9кл/hand</th>
                                                                            <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap" colspan="2">10кл/hand</th>
                                                                            <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap" colspan="2">11кл/hand</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($subjects as $subject)
                                                                            <tr class="hover:bg-gray-200">
                                                                                <td data-label="name" class="border">
                                                                                    <input class="form-control" type="text" name="{{$subject['id']}}[name]" value="{{$subject['name']}}">
                                                                                </td>
                                                                                <td data-label="name-transliteration" class="border">
                                                                                    <input class="form-control" type="text" name="{{$subject['id']}}[name_transliteration]" value="{{$subject['name_transliteration']}}">
                                                                                </td>
                                                                                <td data-label="shortcode" class="border">
                                                                                    <input class="form-control" type="text" name="{{$subject['id']}}[shortcode]" value="{{$subject['shortcode']}}">
                                                                                </td>
                                                                                <td data-label="field_name" class="border">
                                                                                    <input class="form-control" type="text" name="{{$subject['id']}}[field_name]" value="{{$subject['field_name']}}">
                                                                                </td>
                                                                                {{-- classes --}}
                                                                                @for($cl = 5; $cl <= 11; $cl++)
                                                                                    <td data-label="{{$subject['id']}}-for_class_{{$cl}}" class="border">
                                                                                        <input
                                                                                            class=""
                                                                                            type="checkbox"
                                                                                            name="{{$subject['id']}}[classes][{{$cl}}][belongs]"
                                                                                               @if(isset($subject_classes[$subject['id']][$cl]['belongs']))
                                                                                                   checked
                                                                                                    value="true"
                                                                                               @endif

                                                                                        >
                                                                                    </td>
                                                                                    <td data-label="field_name" class="border">
                                                                                        <input class="" type="checkbox" name="{{$subject['id']}}[classes][{{$cl}}][hand_only]"
                                                                                               @if(
                                                                                                        isset($subject_classes[$subject['id']][$cl]['hand_only']) &&
                                                                                                        $subject_classes[$subject['id']][$cl]['hand_only'] == 1
                                                                                                    )
                                                                                                   checked
                                                                                               value="true"
                                                                                               @endif
                                                                                        >
                                                                                    </td>
                                                                                @endfor

                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    @else
                                                                    <div>Жодного предмета не було додано</div>
                                                                    @endif
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
                                <button class="btn btn-warning w-24 mr-1 mb-2">Зберегти</button>
                            </div>
                        </div>
                    </form>

                    <form method="post" action="{{route('admin-create-subject')}}" enctype="multipart/form-data">
                            <div id="faq-accordion-1" class="accordion p-5">
                                <div class="accordion-item">
                                    <div id="faq-accordion-collapse-3" aria-labelledby="faq-accordion-content-3"
                                         data-bs-parent="#faq-accordion-1" style="display: block;">
                                        <div class="text-gray-700 dark:text-gray-600 leading-relaxed">
                                            <div style="height: 15px"></div>
                                            <div align="center"
                                                 class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                <div>
                                                    <div id="subjects-list" class="">

                                                        <div class="intro-y box mt-5 ">

                                                            <div class="p-5" id="hoverable-table">
                                                                <div class="preview" style="display: block; opacity: 1;">
                                                                    <div class="container">
                                                                        <table class = "resp-tab">
                                                                            <thead>
                                                                            <tr>
                                                                                <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Назва</th>
                                                                                <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Транслітерація</th>
                                                                                <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Шорткод</th>
                                                                                <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Значення поля користувача</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr class="hover:bg-gray-200">
                                                                                    <td data-label="name" class="border">
                                                                                        <input class="form-control" type="text" name="name" value="">
                                                                                    </td>
                                                                                    <td data-label="name_transliteration" class="border">
                                                                                        <input class="form-control" type="text" name="name_transliteration" value="">
                                                                                    </td>
                                                                                    <td data-label="shortcode" class="border">
                                                                                        <input class="form-control" type="text" name="shortcode" value="">
                                                                                    </td>
                                                                                    <td data-label="field_name" class="border">
                                                                                        <input class="form-control" type="text" name="field_name" value="">
                                                                                    </td>
                                                                                </tr>
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
                                </div>
                                <div class="accordion-item"></div>
                            </div>
                            <div align="center">
                                <div class="text-right mt-5">
                                    <button class="btn btn-warning w-24 mr-1 mb-2">Створити новий</button>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@include('constant')
@include('js_function')

<style>
    .learning-format-form-wrapper {
        display: grid;
        row-gap: 12px;
        justify-items: stretch;
        justify-content: start;
        padding: 1.5rem;
        margin-right: 0;
        margin-left: 0;
        border-width: 0.2rem;
        position: relative;
        margin: 1rem -15px 0;
        border: solid #f7f7f9;
    }
    .learning-format-form-wrapper .form-group {
        display: grid;
        justify-items: start;
        min-width: 100%;
    }
    .learning-format-form {
        row-gap: 12px;
        display: grid;
    }
    .d-flex {
        display: flex;
    }
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
        .format-inputs {
            flex-wrap: wrap;
            row-gap: 12px;
        }
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

