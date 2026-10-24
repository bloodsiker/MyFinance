<?php

foreach ($children as $key => $child){
    if ($child['tests'] === null){
        $children[$key]['tests'] = [
            [
                'all' => 1,
                'completed' => 0,
            ],
            [
                'all' => 1,
                'completed' => 0,
            ],
        ];
    }
}
?>
@extends('../layout/' . $layout)

@section('subhead')
    <title>Успішність</title>
@endsection
@section('subcontent')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center h-10"></div>
                    <div align="center">
                        <div class="boxed-tabs nav nav-tabs justify-center border border-gray-400 border-dashed text-gray-600 dark:border-gray-700 dark:bg-dark-3 dark:text-gray-500 rounded-md p-1 mt-5 mx-auto" role="tablist">
                            @foreach($children as $key => $child)
                                @if($key===0)
                                    <a onclick="tabForm({{$child['id']}})" data-toggle="tab" data-target="#inactive-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2 active" id="inactive-users-tab" role="tab" aria-selected="true">{{$child['name']}}</a>
                                @else
                                    <a onclick="tabForm({{$child['id']}})" data-toggle="tab" data-target="#active-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2" id="active-users-tab" role="tab" aria-controls="active-users" aria-selected="false">{{$child['name']}}</a>
                                @endif
                            @endforeach
                        </div>
                        <div style="height: 15px"></div>
                        @foreach($children as $key => $child)
                            <div id="children_{{$child['id']}}">
                                @if($key!==0)
                                    <script>
                                        document.getElementById('children_{{$child['id']}}').style = 'display: none;';
                                    </script>
                                @endif
                                <div {{--class="intro-y box p-5" style="border-radius: 5px;box-shadow: 0 0 10px rgba(92,92,92,0.5);background: #f1f5f8;"--}}>

{{--                                    <div class="grid grid-cols-3   gap-3 mt-5 ">--}}

{{--                                        <div class="col-span-3 sm:col-span-1 lg:col-span-1 mt-8">--}}

{{--                                            <div class="intro-x">--}}
{{--                                                <div class="box px-5 py-3 mb-3 flex items-center zoom-in">--}}
{{--                                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">--}}
{{--                                                        <img alt="Rubick Tailwind HTML Admin Template" src="{{$child['photo']}}">--}}
{{--                                                    </div>--}}
{{--                                                    <div class="ml-4 mr-auto">--}}
{{--                                                        <div class="font-medium">{{$child['full_name']}}</div>--}}
{{--                                                        <div class="text-gray-600 text-xs mt-0.5" style="text-align: left;">{{$child['class']}} клас</div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                    </div>--}}

                                    <div class="mt-5 w-100">
                                        <div class="box intro-x px-5 py-3 mb-3 zoom-in zoom-in__small child-block__wrapper">
                                            <div class="flex items-center child-block__info">
                                                <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                                    <img alt="Rubick Tailwind HTML Admin Template" src="{{$child['photo']}}">
                                                </div>
                                                <div class="ml-4 mr-auto">
                                                    <div class="font-medium">{{$child['full_name']}}</div>
                                                    <div class="text-gray-600 text-xs mt-0.5" style="text-align: left;">{{$child['class']}} клас</div>
                                                </div>
                                            </div>
                                            <div class="auth-link-wrapper child-block__action">
                                                <div class="auth-link-message">
                                                    *для того щоб залогінитись до кабінету учня потрібен логін пароль, його дізнатись можно у Вашої дитини або звернутись до клієнтьского сервісу
                                                </div>
                                                <a href="{{config('app.MOODLE_DOMAIN')}}" class="btn btn-warning auth-link">
                                                    Перейти до кабінету учня
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="height: 15px"></div>

                                    <div class="grid grid-cols-12 gap-6 mt-5">

                                        <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <div class="ml-auto">

                                                            <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer">
                                                                {{$procent(current($child['tests'])['completed'], current($child['tests'])['all'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{$child['performance']['test']}}</div>
                                                    <div class="text-base text-gray-600 mt-1">Здано тестів</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <div class="ml-auto">
                                                            <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer">
                                                                {{$procent(next($child['tests'])['completed'], current($child['tests'])['all'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{$child['performance']['practice']}}</div>
                                                    <div class="text-base text-gray-600 mt-1">Проектні роботи</div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <div class="ml-auto">
                                                            <div style="visibility: hidden;">
                                                                {{$procent(10,100)}} <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-up w-4 h-4 ml-0.5"><polyline points="18 15 12 9 6 15"></polyline></svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{$child['performance']['time']}}</div>
                                                    <div class="text-base text-gray-600 mt-1">Средній час на платформі ( в день)</div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <a href="@if(is_null($child['moodle_id'])) # @else {{route('grades', ['child' => $child['id']])}} @endif">
                                                        <div class="flex">
                                                            <div class="ml-auto">
                                                                <div style="visibility: hidden;" class="report-box__indicator bg-theme-9 tooltip cursor-pointer">
                                                                    {{$procent(10,100)}} <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-up w-4 h-4 ml-0.5"><polyline points="18 15 12 9 6 15"></polyline></svg>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-3xl font-medium leading-8 mt-6">{{round($child['performance']['rating'], 0)}}</div>
                                                        <div class="text-base text-gray-600 mt-1">Середня оцінка</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="height: 25px"></div>


                                    <div class="grid grid-cols-12 gap-2">

                                        <div class="col-span-12">
                                            <div class="intro-y box mt-5 ">
                                                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                                    <h2 class="font-medium text-base mr-auto">ЦІЛІ, НА НАВЧАЛЬНИЙ РІК</h2>
                                                    <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                                                        <a href="{{route('change-goals', ['child' => $child['id']])}}" class="btn btn-warning w-24 mr-1 mb-2">
                                                            Змінити
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="p-5" id="hoverable-table">
                                                    <div class="preview" style="display: block; opacity: 1;">
                                                        <div class="overflow-x-auto">
                                                            <table class="table resp-tab">
                                                                <thead>
                                                                <tr>
                                                                    {{-- <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">#</th> --}}
                                                                    <th data-label="gредмет" class="border border-b-2 dark:border-dark-5 whitespace-nowrap">предмет</th>
                                                                    <th data-label="за минулий рік" class="border border-b-2 dark:border-dark-5 whitespace-nowrap">за минулий рік</th>
                                                                    <th data-label="поточна оцінка" class="border border-b-2 dark:border-dark-5 whitespace-nowrap">поточна оцінка</th>
                                                                    <th data-label="ціль" class="border border-b-2 dark:border-dark-5 whitespace-nowrap">ціль</th>
                                                                    <th data-label="знати" class="border border-b-2 dark:border-dark-5 whitespace-nowrap">знати</th>
                                                                    <th data-label="здати" class="border border-b-2 dark:border-dark-5 whitespace-nowrap">здати</th>

                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @if($child['target'] !== null)
                                                                    @foreach($child['target'] as $index => $item)
                                                                        <tr class="hover:bg-gray-200">
                                                                            {{-- <td class="border">{{$loop->index+1}}</td> --}}
                                                                            <td class="border">{{$item['name']}}</td>
                                                                            <td data-label="за минулий рік" class="border">{{$item['current']}}</td>
                                                                            <td data-label="поточна оцінка" class="border">{{$item['now']??null}}</td>
                                                                            <td data-label="ціль" class="border">{{$item['target']}}</td>
                                                                            @if(empty($item['radio']))
                                                                                {{$item['radio'] = 'hand'}}
                                                                            @endif
                                                                            <td data-label="знати" class="border">
                                                                                <input disabled {{$item['radio']==='know'?'checked':''}} type="checkbox">
                                                                            </td>
                                                                            <td data-label="здати" class="border">
                                                                                <input disabled {{$item['radio']==='know'?'':'checked'}} type="checkbox">
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div style="height: 15px"></div>
                                        </div>


                                        <div class="col-span-12">
                                            <div class="intro-y box mt-5 ">
                                                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                                    <h2 class="font-medium text-base mr-auto">ВИДИ ІНТЕЛЕКТІВ</h2>
                                                    <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                                                        <a href="{{route('change-mentality', ['child' => $child['id']])}}" class="btn btn-warning w-24 mr-1 mb-2">
                                                            Змінити
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="p-5" id="hoverable-table">
                                                    <div class="preview" style="display: block; opacity: 1;">
                                                        <div class="overflow-x-auto">
                                                            <table class="table resp-tab">
                                                                <thead>
                                                                <tr>
                                                                    <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Вид</th>
                                                                    <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Відсотки</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>


                                                                <tr class="hover:bg-gray-200">
                                                                    <td class="border">вербально-лінгвістичний</td>
                                                                    <td class="border">{{$child['mentality']['verbal-linguistic'] ?? null}}%</td>
                                                                </tr>

                                                                <tr class="hover:bg-gray-200">
                                                                    <td class="border">логіко-математичний</td>
                                                                    <td class="border">{{$child['mentality']['logical-mathematical'] ?? null}}%</td>
                                                                </tr>

                                                                <tr class="hover:bg-gray-200">
                                                                    <td  class="border">музичний</td>
                                                                    <td class="border">{{$child['mentality']['musical'] ?? null}}%</td>
                                                                </tr>

                                                                <tr class="hover:bg-gray-200">
                                                                    <td  class="border">натуралістичний</td>
                                                                    <td class="border">{{$child['mentality']['naturalistic'] ?? null}}%</td>
                                                                </tr>

                                                                <tr class="hover:bg-gray-200">
                                                                    <td  class="border">візуально-просторовий</td>
                                                                    <td class="border">{{$child['mentality']['spatial'] ?? null}}%</td>
                                                                </tr>

                                                                <tr class="hover:bg-gray-200">
                                                                    <td  class="border">кінестетичний</td>
                                                                    <td class="border">{{$child['mentality']['kinesthetic'] ?? null}}%</td>
                                                                </tr>

                                                                <tr class="hover:bg-gray-200">
                                                                    <td  class="border">міжособистісний</td>
                                                                    <td class="border">{{$child['mentality']['missocial'] ?? null}}%</td>
                                                                </tr>

                                                                <tr class="hover:bg-gray-200">
                                                                    <td  class="border">внутрішньоособистісний</td>
                                                                    <td class="border">{{$child['mentality']['intrapersonal'] ?? null}}%</td>
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="intro-y box mt-5 ">
                                                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                                    <h2 class="font-medium text-base mr-auto">МУЛЬТИТЕСТ</h2>
                                                    <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                                                        <a href="{{route('change-skill', ['child' => $child['id']])}}" class="btn btn-warning w-24 mr-1 mb-2">
                                                            Змінити
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="p-5" id="hoverable-table">
                                                    <div class="preview" style="display: block; opacity: 1;">
                                                        <div class="overflow-x-auto">
                                                            <table class="table resp-tab">
                                                                <thead>
                                                                <tr>
                                                                    <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Навик</th>
                                                                    <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Бал</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr class="hover:bg-gray-200">
                                                                        <td class="border">Навчальні навички</td>
                                                                        <td class="border">
                                                                            @if(isset($child['skill']['study']))
                                                                                {{ $child['skill']['study'] }} з 50
                                                                            @else
                                                                                Тест не пройдений
                                                                            @endif
                                                                        </td>
                                                                    </tr>

                                                                    <tr class="hover:bg-gray-200">
                                                                        <td class="border">Технічні навички</td>
                                                                        <td class="border">
                                                                            @if(isset($child['skill']['technical']))
                                                                                {{ $child['skill']['technical'] }} з 50
                                                                            @else
                                                                                Тест не пройдений
                                                                            @endif
                                                                        </td>
                                                                    </tr>

                                                                    <tr class="hover:bg-gray-200">
                                                                        <td  class="border">Життєві навички</td>
                                                                        <td class="border">
                                                                            @if(isset($child['skill']['life']))
                                                                                {{ $child['skill']['life'] }} з 50
                                                                            @else
                                                                                Тест не пройдений
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($child['training_format']['format_type'] == 'parallel')
                                            @include('cabinet/academic-parallel')
                                        @else
                                            @include('cabinet/academic-consistent')
                                        @endif
                                    </div>

                                    <div style="height: 15px"></div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


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
@media(max-width: 768px) {
    .resp-tab thead {
        display: none;
    }

    table td .input{
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
@media(max-width: 480px) {
    .resp-tab td {
        width: 100%;
    }
}








/*
        .container {
        min-width: 200px;
        max-width: 100%;
        padding: 0 5px;
        box-sizing: border-box;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin: 20px 0;
    }

    table td, table th {
        padding: 10px;

        /* border: 1px solid #cbbdbd; */
    /*}
     */

    /* @media (max-width: 720px) {
        .container table thead {
        display: none;
    }
    .container table tr {

    display: block;

    }
    .container table td {
        display: flex;
        justify-content: space-between;
        font-size: 14px;


    }
    .container table td .input{
        width: 30%;
    }
    .container table td::before {
        content: attr(data-label);
        font-weight: bold;
        margin-right: 20px;

    }
    } */
    </style>












@endsection
<script>
    function tabForm(id) {
        @foreach($children as $value)
        document.getElementById('children_{{$value['id']}}').style = 'display: none;';
        @endforeach
        document.getElementById(`children_${id}`).style = '';
    }
</script>
