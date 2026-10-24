@extends('../layout/' . $layout)

@section('subhead')
    <title>Налаштування навчальних форматів</title>
@endsection

@section('subcontent')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">

                    <div class="intro-y flex items-center h-10"></div>
                    <div class="intro-y flex items-center h-30">
                        <h2 style="width: 100%" class="text-center text-lg font-medium  mr-5">Налаштування навчальних форматів</h2>
                    </div>
                    <br>
                    {{--<div class="alert alert-warning show flex items-center mb-2" role="alert">
                        <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> Всі поля обовʼязкові для заповненя
                    </div>--}}

                    <div class="intro-y flex items-center h-10"></div>

                    <div class="intro-y box p-5">
                    <div class="learning-format-form-wrapper">
                        <form class="learning-format-form">
                            <div class="form-group">
                                <label for="learningFormatSelect">Список форматiв</label>
                                <select onchange="document.location.href = this.value" class="form-select form-select-sm" id="learningFormatSelect">
                                    <option value="{{route( 'manage-learning-formats')}}" selected="selected">Обрати формат зі списку</option>
                                    <option value="{{route('create-format', ['format-type' => 'parallel'])}}">Створити параллельий формат</option>
                                    <option value="{{route('create-format', ['format-type' => 'consistent'])}}">Створити послідовний формат</option>
                                    @foreach($formats as $format)
                                        <option
                                            value="{{route( 'manage-learning-formats', ['id' => $format['id'] ] ) }}"
                                            @if(isset($editing_format) && $format['id'] == $editing_format['id']) selected @endif
                                        >
                                            {{$format['name']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                {{--<label for="learningFormatTypeSelect">Тип форматiв</label>
                                  <select class="form-select form-select-sm" id="learningFormatTypeSelect">
                                      <option value="consistence">Послідовний</option>
                                      <option value="parallel" selected>Параллельний</option>
                                      <option value="custom">Індивідуальний</option>
                                  </select>--}}
                                @if(isset($editing_format))
                                    <label for="learningFormatTypeView">Тип формату: <b>{{config('constant.'.$editing_format['type'])}}</b></label>
                                @endif
                            </div>
                        </form>
                    </div>

                        @yield('editing_format_form')

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

