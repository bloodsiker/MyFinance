<?php
if (Auth::user()->stageRegister === 8) {
    $att = 'disabled';
} else {
    $att = '';
}

$tariffs = \App\Helpers\PaymentHelpers::getTariffData();
?>
@extends('../layout/' . $layout)

@section('subhead')
    <title>Процес реєстрації</title>
@endsection

@section('subcontent')
    <script>var sum = []</script>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">

                    @if(Auth::user()->stageRegister === 8)
                        @include('progress', ['colors'=>['bg-theme-12','bg-theme-12','bg-theme-9','bg-theme-9','bg-theme-9']])
                    @else
                        @include('progress', ['colors'=>['bg-theme-9','bg-theme-12','bg-gray-200','bg-gray-200','bg-gray-200']])
                    @endif

                    <div class="boxed-tabs nav nav-tabs justify-center border border-gray-400 border-dashed text-gray-600 dark:border-gray-700 dark:bg-dark-3 dark:text-gray-500 rounded-md p-1 mt-5 mx-auto" role="tablist">
                        @foreach($item as $key => $children)
                            <script>sum[`{{$children['id']}}`] = 0</script>
                            @if($key===0)
                                <a onclick="tabForm({{$children['id']}})" data-toggle="tab" data-target="#inactive-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2 active" id="inactive-users-tab" role="tab" aria-selected="true">{{$children['name']}}</a>
                            @else
                                <a onclick="tabForm({{$children['id']}})" data-toggle="tab" data-target="#active-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2" id="active-users-tab" role="tab" aria-controls="active-users" aria-selected="false">{{$children['name']}}</a>
                            @endif
                        @endforeach

<style>
    .header-styler {
        background-color: orange;
        padding: 20px;
        border-radius: 25px;
    }
    // .accordion-body {
    //     background-color: lavender;
    //     padding: 20px;
    //     border-radius: 25px;
    // }
</style>


                    </div>
                    <div class="intro-y flex items-center h-10"></div>
                    <div class="intro-y flex items-center h-10">
                        <h2 style="width: 100%" class="text-center text-lg font-medium truncate mr-5">Оберіть послуги для своєї дитини</h2>
                    </div>
                    <div class="intro-y flex items-center h-10"></div>
                    <form method="post" action="{{route('second_step')}}">
                        <div class="intro-y box p-5">
                            @foreach($item as $key => $children)
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        window.onload = readPrices(`{{$children['id']}}`)
                                    }, false);
                                </script>
                                <input style="display: none" name="{{$children['id']}}[study]"        id="{{$children['id']}}">
                                <input style="display: none" name="{{$children['id']}}[supervising]"  id="{{$children['id']}}">
                                <input style="display: none" name="{{$children['id']}}[additionally]" id="{{$children['id']}}">
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
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-1" aria-expanded="false" aria-controls="faq-accordion-collapse-1">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label for="input-wizard-1" class="text-lg form-label">Оберіть пакет навчання в JAMM School</label>
                                                            <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div id="faq-accordion-collapse-1" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-1" data-bs-parent="#faq-accordion-1" style="display: none;">
                                                    <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                        <div id="faq-accordion-10-{{$children['id']}}" class="accordion p-5">




                                                            <div id="ac-item-1" class="accordion-item">
                                                                <label for="ac-item-1">
                                                                    <h3 class="header-styler" style="text-align: left; margin-bottom: 20px; font-weight: 700; font-size:4vmin" class="truncate mr-5">{!! $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['label'] !!}</h3>
                                                                    </label>
                                                                <div id="faq-accordion-content-11-{{$children['id']}}" class="accordion-header">
                                                                    <div class="grid grid-cols-12 gap-2">
                                                                        <div class="col-span-4"></div>
                                                                        <div class="col-span-4">
                                                                            <button class="accordion-button collapsed-{{$children['id']}}" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-11-{{$children['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-11-{{$children['id']}}">
                                                                            Обрати
                                                                                </button>
                                                                        </div>
                                                                        <input checked required onchange="document.getElementById(`{{$children['id']}}[study]`).value='{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['value']}}';readPrices(`{{$children['id']}}`);
                                                                            /*document.getElementById(`accordion-item={{$children['id']}}-supervising`).style     = '';*/
                                                                            /*document.getElementById(`accordion-item={{$children['id']}}-additionally`).style    = '';*/"
                                                                               class="show-code col-span-2 form-check-switch mr-0 ml-3" id="{{$children['id']}}[study]"
                                                                               name="{{$children['id']}}[study]" value="{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['value']}}" type="radio">
                                                                    </div>
                                                                </div>
                                                                <div align="left" id="faq-accordion-collapse-11-{{$children['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-11-{{$children['id']}}" data-bs-parent="#faq-accordion-10-{{$children['id']}}" style="display: none;">
                                                                    <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                        <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">
                                                                            <div class="intro-y col-span-12 col-span-3"></div>
                                                                            <div class="intro-y col-span-12 xl:col-span-9">

                                                                                <h3>
                                                                                    <ul>
                                                                                        <li> &#10003; Вибір швидкості, часу і місця навчання </li>
                                                                                        <li> &#10003; Електронні підручники та навчальні матеріали </li>
                                                                                        <li> &#10003; Записи уроків і тести для перевірки знань </li>
                                                                                        <li> &#10003; Соціалізація – “Уроки Єдності” </li>
                                                                                        <li> &#10003; Живі вебінари у реальному часі </li>
                                                                                        <li> &#10003; Індивідуальні консультації та онлайн-чати з вчителями </li>
                                                                                        <li> &#10003; Щотижневі зустрічі з куратором, спілкування з однолітками, обговорення актуальних для дітей тем, цікаві зустрічі та події </li>
                                                                                        <li> &#10003; Документ про навчання державного зразка, або виписку з оцінками ( в кабінеті учня ) </li>
                                                                                    </ul>

                                                                                </h3>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div style="padding:20px; border: 5px solid ; border-radius:25px; margin:20px">
                                                                                        <h1 style="font-size: 30px; font-weight: 700">{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['price_actual']}} грн <p style="text-decoration: line-through; font-size: 20px;">замість {{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['price_old']}} грн<p> </h1>

                                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div id="ac-item-2" class="accordion-item">
                                                                <label for="ac-item-2" >
                                                                    <h3 class = "header-styler" style="text-align: left; margin-bottom: 20px; font-weight: 700; font-size:4vmin" style="width: 100%" class="truncate mr-5">{!! $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['label'] !!}</h3>

                                                                </label>
                                                                <div id="faq-accordion-content-12-{{$children['id']}}" class="accordion-header">
                                                                    <div class="grid grid-cols-12 gap-2">
                                                                        <div class="col-span-4"></div>
                                                                        <div class="col-span-4">
                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-12-{{$children['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-12-{{$children['id']}}">
                                                                            Обрати
                                                                                </button>
                                                                        </div>
                                                                        <input required onchange="document.getElementById(`{{$children['id']}}[study]`).value='{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['value']}}';readPrices(`{{$children['id']}}`);
                                                                            /*document.getElementById(`accordion-item={{$children['id']}}-supervising`).style = '';*/
                                                                            /*document.getElementById(`accordion-item={{$children['id']}}-additionally`).style = '';*/"
                                                                               class="show-code col-span-2 form-check-switch mr-0 ml-3" id="{{$children['id']}}[study]"
                                                                               name="{{$children['id']}}[study]" value="{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['value']}}" type="radio">
                                                                    </div>
                                                                </div>
                                                                <div align="left" id="faq-accordion-collapse-12-{{$children['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-12-{{$children['id']}}" data-bs-parent="#faq-accordion-10-{{$children['id']}}" style="display: none;">
                                                                    <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                        <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">
                                                                            <div class="intro-y col-span-12 col-span-3"></div>
                                                                            <div class="intro-y col-span-12 xl:col-span-9">

                                                                                <h3>
                                                                                    <ul>
                                                                                        <li> &#10003; Вибір швидкості, часу і місця навчання </li>
                                                                                        <li> &#10003; Електронні підручники та навчальні матеріали </li>
                                                                                        <li> &#10003; Записи уроків і тести для перевірки знань </li>
                                                                                        <li> &#10003; Соціалізація – “Уроки Єдності” </li>
                                                                                        <li> &#10003; Живі вебінари у реальному часі </li>
                                                                                        <li> &#10003; Індивідуальні консультації та онлайн-чати з вчителями </li>
                                                                                        <li> &#10003; Щотижневі зустрічі з куратором, спілкування з однолітками, обговорення актуальних для дітей тем, цікаві зустрічі та події </li>
                                                                                        <li> &#10003; Документ про навчання державного зразка, або виписку з оцінками ( в кабінеті учня ) </li>
                                                                                        <li> &#10003; Створення індивідуального плану навчання </li>
                                                                                        <li> &#10003; Супровід супервайзера-куратора </li>
                                                                                        <li> &#10003; Розвиток сильних сторін учня </li>
                                                                                        <li> &#10003; Мотиваційні «живі» зустрічі для розвитку навичок самостійності і самодисципліни </li>
                                                                                    </ul>

                                                                                </h3>
                                                                            </div>
                                                                        </div>
                                                                        <div style="padding:20px; border: 5px solid ; border-radius:25px; margin:20px">
                                                                                        <h1 style="font-size: 30px; font-weight: 700">{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['price_actual']}} грн <p style="text-decoration: line-through; font-size: 20px;">замість {{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['price_old']}} грн<p> </h1>

                                                                                    </div>
                                                                    </div>
                                                                </div>
                                                            </div>

{{--                                                            <div id="ac-item-3" class="accordion-item" style="display: none;">--}}
{{--                                                                <label for="ac-item-3">--}}
{{--                                                                    <h3  class = "header-styler" style="text-align: left;  margin-bottom: 20px; font-weight: 700; font-size:4vmin" style="width: 100%" class="truncate mr-5">{!! $tariffs[\App\Helpers\PaymentHelpers::TARIFF_TEST]['label'] !!}</h3>--}}

{{--                                                                </label>--}}
{{--                                                                <div id="faq-accordion-content-12-{{$children['id']}}" class="accordion-header">--}}
{{--                                                                    <div class="grid grid-cols-12 gap-2">--}}
{{--                                                                        <div class="col-span-4"></div>--}}
{{--                                                                        <div class="col-span-4">--}}
{{--                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-13-{{$children['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-13-{{$children['id']}}">--}}
{{--                                                                            Обрати--}}
{{--                                                                                </button>--}}
{{--                                                                        </div>--}}
{{--                                                                        <input  required onchange="document.getElementById(`{{$children['id']}}[study]`).value='{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_TEST]['value']}}';readPrices(`{{$children['id']}}`);--}}
{{--                                                                            document.getElementById(`accordion-item={{$children['id']}}-supervising`).style = 'display:none';--}}
{{--                                                                            document.getElementById(`accordion-item={{$children['id']}}-additionally`).style = 'display:none';"--}}
{{--                                                                               class="show-code col-span-2 form-check-switch mr-0 ml-3" id="{{$children['id']}}[study]" name="{{$children['id']}}[study]"--}}
{{--                                                                               value="{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_TEST]['value']}}" type="radio">--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                                <div align="left" id="faq-accordion-collapse-13-{{$children['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-13-{{$children['id']}}" data-bs-parent="#faq-accordion-10-{{$children['id']}}" style="display: none;">--}}
{{--                                                                    <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">--}}
{{--                                                                        <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">--}}
{{--                                                                            <div class="intro-y col-span-12 col-span-3"></div>--}}
{{--                                                                            <div class="intro-y col-span-12 xl:col-span-9"><h3>--}}
{{--                                                                                <ul>--}}
{{--                                                                                <li>&#10003; Вибір швидкості, часу і місця навчання</li>--}}
{{--                                                                                <li>&#10003; Електронні підручники та навчальні матеріали</li>--}}
{{--                                                                                <li>&#10003; Записи уроків і тести для перевірки знань</li>--}}
{{--                                                                                <li>&#10003; Можливість отримати виписку за результатами навчання</li>--}}
{{--                                                                                <li>&#10003; Соціалізація – “Уроки Єдності”</li>--}}
{{--                                                                                </ul>--}}

{{--                                                                                </h3>--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
{{--                                                                        <div style="padding:20px; border: 5px solid ; border-radius:25px; margin:20px">--}}
{{--                                                                                        <h1 style="font-size: 30px; font-weight: 700">{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_TEST]['price_actual']}} грн <p style="text-decoration: line-through; font-size: 20px;">замість {{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_TEST]['price_old']}} грн<p> </h1>--}}

{{--                                                                                    </div>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

{{--                                            <div id="accordion-item={{$children['id']}}-supervising" class="accordion-item" style="display: none;">--}}
{{--                                                <div id="faq-accordion-content-2" class="accordion-header">--}}

{{--                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-2" aria-expanded="false" aria-controls="faq-accordion-collapse-2">--}}
{{--                                                        <div class="intro-y col-span-12 sm:col-span-6">--}}
{{--                                                            <label for="input-wizard-1" class="text-lg form-label">Додаткові послуги</label>--}}
{{--                                                            <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>--}}
{{--                                                        </div>--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}

{{--                                                <div id="faq-accordion-collapse-2" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-2" data-bs-parent="#faq-accordion-1" style="display: none;">--}}
{{--                                                    <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">--}}
{{--                                                        <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">--}}
{{--                                                                <div id="faq-accordion-20-{{$children['id']}}" class="accordion p-4">--}}

{{--                                                                    <div id="ad-ac-item1" class="accordion-item" >--}}
{{--                                                                        <label for="ad-ac-item1" style="align-content: left">--}}
{{--                                                                            <h3  class = "header-styler" style="text-align: left; margin-bottom: 20px; font-weight: 700; font-size:4vmin" class="truncate mr-5">Відсутнiй</h3>--}}

{{--                                                                        </label>--}}
{{--                                                                        <div id="faq-accordion-content-21-{{$children['id']}}" class="accordion-header">--}}
{{--                                                                            <div class="grid grid-cols-12 gap-2">--}}
{{--                                                                                <div class="col-span-4"></div>--}}
{{--                                                                                <div class="col-span-4">--}}
{{--                                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-21-{{$children['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-21-{{$children['id']}}">--}}
{{--                                                                                        Відключити послугу--}}
{{--                                                                                    </button>--}}
{{--                                                                                </div>--}}
{{--                                                                                <input onchange="document.getElementById(`{{$children['id']}}[supervising]`).value = 'off';readPrices(`{{$children['id']}}`)" checked class="show-code col-span-2 form-check-switch mr-0 ml-3" id="{{$children['id']}}[supervising]" name="{{$children['id']}}[supervising]" value="off" type="radio">--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}


{{--                                                                        <div align="left" id="faq-accordion-collapse-21-{{$children['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-21-{{$children['id']}}" data-bs-parent="#faq-accordion-20-{{$children['id']}}" style="display: none;">--}}
{{--                                                                            <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">--}}
{{--                                                                                <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-3"></div>--}}
{{--                                                                                    <div class="intro-y col-span-12 xl:col-span-9">--}}
{{--                                                                                        <h3>--}}

{{--                                                                                        </h3>--}}
{{--                                                                                    </div>--}}
{{--                                                                                </div>--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}

{{--                                                                    </div>--}}

{{--                                                                    <div class="accordion-item" style = "display:none">--}}
{{--                                                                        <div id="faq-accordion-content-21-{{$children['id']}}" class="accordion-header">--}}
{{--                                                                            <div class="grid grid-cols-12 gap-2">--}}
{{--                                                                                <div class="col-span-4"></div>--}}
{{--                                                                                <div class="col-span-4">--}}
{{--                                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-21-{{$children['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-21-{{$children['id']}}">--}}
{{--                                                                                        <h3 style="width: 100%" class="truncate mr-5">Щоденний ( Набір зупинено )</h3>--}}
{{--                                                                                    </button>--}}
{{--                                                                                </div>--}}
{{--                                                                                <input onchange="document.getElementById(`{{$children['id']}}[supervising]`).value = 'daily';readPrices(`{{$children['id']}}`)" class="show-code col-span-2 form-check-switch mr-0 ml-3" id="{{$children['id']}}[supervising]" name="{{$children['id']}}[supervising]" value="daily" type="radio">--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
{{--                                                                        <div align="left" id="faq-accordion-collapse-21-{{$children['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-21-{{$children['id']}}" data-bs-parent="#faq-accordion-20-{{$children['id']}}" style="display: none;">--}}
{{--                                                                            <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">--}}
{{--                                                                                <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-3"></div>--}}
{{--                                                                                    <div class="intro-y col-span-12 xl:col-span-9"><h3>--}}
{{--                                                                                            - Щоденні зустрічі групи учнів з супервайзером<br>--}}
{{--                                                                                            - Постановка цілей на тиждень<br>--}}
{{--                                                                                            - Щоденний моніторинг процесу навчання, розвиток самостійності<br>--}}
{{--                                                                                            - Щотижневий звіт супервайзеру про досягнення цілей<br>--}}
{{--                                                                                        </h3>--}}
{{--                                                                                    </div>--}}
{{--                                                                                </div>--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}

{{--                                                                    <div id="ad-ac-item-2" class="accordion-item">--}}
{{--                                                                        <label for="ad-ac-item-2" style="align-content: center">--}}
{{--                                                                        <h3 class = "header-styler" style="text-align: left; margin-bottom:20px; font-weight: 700; font-size:4vmin" style="width: 100%" class="truncate mr-5">Супровід супервайзера-куратора</h3>--}}
{{--                                                                        </label>--}}
{{--                                                                        <div id="faq-accordion-content-22-{{$children['id']}}" class="accordion-header">--}}
{{--                                                                            <div class="grid grid-cols-12 ">--}}
{{--                                                                                <div class="col-span-4">--}}

{{--                                                                                    </div>--}}
{{--                                                                                <div class="col-span-4">--}}


{{--                                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-22-{{$children['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-22-{{$children['id']}}">--}}
{{--                                                                                    <h3 style="font-weight: 700; font-size:3vmin" style="width: 100%" class="truncate mr-5"></h3>--}}
{{--                                                                                    Обрати--}}
{{--                                                                                    </button>--}}
{{--                                                                                </div>--}}
{{--                                                                                <input onchange="document.getElementById(`{{$children['id']}}[supervising]`).value = 'weekly';readPrices(`{{$children['id']}}`)" class="show-code col-span-2 form-check-switch mr-0 ml-3" id="{{$children['id']}}[supervising]" name="{{$children['id']}}[supervising]" value="weekly" type="radio">--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
{{--                                                                        <div align="left" id="faq-accordion-collapse-22-{{$children['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-22-{{$children['id']}}" data-bs-parent="#faq-accordion-20-{{$children['id']}}" style="display: none;">--}}
{{--                                                                            <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">--}}
{{--                                                                                <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-3"></div>--}}
{{--                                                                                    <div class="intro-y col-span-12 xl:col-span-9"><h3>--}}
{{--                                                                                        <ul>--}}
{{--                                                                                        <li> &#10003; Створення індивідуального плану навчання </li>--}}
{{--                                                                                        <li> &#10003; Супровід супервайзера-куратора </li>--}}
{{--                                                                                        <li> &#10003; Розвиток сильних сторін учня </li>--}}
{{--                                                                                        <li> &#10003; Мотиваційні «живі» зустрічі для розвитку навичок самостійності і самодисципліни </li>--}}
{{--                                                                                        </ul>--}}
{{--                                                                                        </h3>--}}
{{--                                                                                    </div>--}}
{{--                                                                                </div>--}}
{{--                                                                                <div style="padding:20px; border: 5px solid ; border-radius:25px; margin:20px">--}}
{{--                                                                                        <h1 style="font-size: 30px; font-weight: 700">2000 грн</h1>--}}

{{--                                                                                    </div>--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}


{{--                                                                    <div class="accordion-item" style = "display:none">--}}
{{--                                                                        <div id="faq-accordion-content-23-{{$children['id']}}" class="accordion-header">--}}
{{--                                                                            <div class="grid grid-cols-12 gap-2">--}}
{{--                                                                                <div class="col-span-4"></div>--}}
{{--                                                                                <div class="col-span-4">--}}
{{--                                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-23-{{$children['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-23-{{$children['id']}}">--}}
{{--                                                                                        <h3 style="width: 100%" class="truncate mr-5">Креш-курс ( НАБІР ПРИЗУПИНЕНО )</h3>--}}
{{--                                                                                    </button>--}}
{{--                                                                                </div>--}}
{{--                                                                                <input onchange="document.getElementById(`{{$children['id']}}[supervising]`).value = 'crash';readPrices(`{{$children['id']}}`)" class="show-code col-span-2 form-check-switch mr-0 ml-3" id="{{$children['id']}}[supervising]" name="{{$children['id']}}[supervising]" value="crash" type="radio">--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
{{--                                                                        <div align="left" id="faq-accordion-collapse-23-{{$children['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-23-{{$children['id']}}" data-bs-parent="#faq-accordion-20-{{$children['id']}}" style="display: none;">--}}
{{--                                                                            <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">--}}
{{--                                                                                <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-3"></div>--}}
{{--                                                                                    <div class="intro-y col-span-12 xl:col-span-9"><h3>--}}
{{--                                                                                            - Онбординг учня<br>--}}
{{--                                                                                            - Ознайомлення з відмінностями JS від інших шкіл<br>--}}
{{--                                                                                            - Формування базових навичок роботи з нашою навчальною онлайн платформою<br>--}}
{{--                                                                                            - Формування базового розкладу<br>--}}
{{--                                                                                        </h3>--}}
{{--                                                                                    </div>--}}
{{--                                                                                </div>--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                            <div style="display: none" id="accordion-item={{$children['id']}}-additionally" class="accordion-item">--}}
{{--                                                <div id="faq-accordion-content-3" class="accordion-header">--}}

{{--                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-3" aria-expanded="false" aria-controls="faq-accordion-collapse-3">--}}
{{--                                                        <div class="intro-y col-span-12 sm:col-span-6">--}}
{{--                                                            <label for="input-wizard-1" class="text-lg form-label">Додаткові послуги</label>--}}
{{--                                                            <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>--}}
{{--                                                        </div>--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                                <input style="display: none" hidden name="{{$children['id']}}[additionally]" id="{{$children['id']}}[additionally]">--}}
{{--                                                <div id="faq-accordion-collapse-3" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-3" data-bs-parent="#faq-accordion-1" style="display: none;">--}}
{{--                                                    <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">--}}
{{--                                                        <div style="width: 85%;" class="intro-y box">--}}

{{--                                                            <div style="border-radius: 5px;box-shadow: 0 0 10px rgba(92,92,92,0.5);" id="faq-accordion-30-{{$children['id']}}" class="accordion p-5">--}}
{{--                                                                <div class="accordion-item">--}}
{{--                                                                    <div id="faq-accordion-content-31-{{$children['id']}}" class="accordion-header">--}}
{{--                                                                        <div align="right" class="grid grid-cols-12 gap-2">--}}
{{--                                                                            <div class="xxl:col-span-5"></div>--}}
{{--                                                                            <div class="col-span-2">--}}
{{--                                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-31-{{$children['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-31-{{$children['id']}}">--}}
{{--                                                                                    <h3 style="width: 100%" class="text-center truncate mr-5">Підготовка до ДПА/ЗНО<br>старша школа</h3>--}}
{{--                                                                                </button>--}}
{{--                                                                            </div>--}}
{{--                                                                            <input onclick="blockMark('{{$children['id']}}');" class="col-span-1" id="{{$children['id']}}[att]" name="{{$children['id']}}[additionally][tutoring]" type="checkbox">--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                    <div id="faq-accordion-collapse-31-{{$children['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-31-{{$children['id']}}" data-bs-parent="#faq-accordion-30-{{$children['id']}}" style="display: none;">--}}
{{--                                                                        <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">--}}
{{--                                                                            <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">--}}
{{--                                                                                <div class="font-medium text-base">--}}
{{--                                                                                    Хочеш вивчати предмети глибше, швидше обирай репетитора і стартуй поглиблене навчання.<br>--}}
{{--                                                                                    ( після вибору предметів, менеджер зв'яжеться з Вами для вибору часу для додаткових зустрічей з вчителем )<br>--}}
{{--                                                                                </div>--}}
{{--                                                                                <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">--}}

{{--                                                                                    <div align="left" class="intro-y col-span-12 col-span-2">--}}
{{--                                                                                        <label for="input-wizard-1" class="form-label">Алгебра</label>--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-2">--}}
{{--                                                                                        <input disabled onclick="readPrices(`{{$children['id']}}`);valueAdd('{{$children['id']}}')" id="{{$children['id']}}tutoring-1" name="" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-2"></div>--}}

{{--                                                                                    <div align="left" class="intro-y col-span-12 col-span-3">--}}
{{--                                                                                        <label for="input-wizard-2" class="form-label">Геометрія</label>--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-3">--}}
{{--                                                                                        <input disabled onclick="readPrices(`{{$children['id']}}`);valueAdd('{{$children['id']}}')" id="{{$children['id']}}tutoring-2" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">--}}
{{--                                                                                    </div>--}}


{{--                                                                                    <div align="left" class="intro-y col-span-12 col-span-2">--}}
{{--                                                                                        <label for="input-wizard-3" class="form-label"> Українська мова/література</label>--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-2">--}}
{{--                                                                                        <input disabled onclick="readPrices(`{{$children['id']}}`);valueAdd('{{$children['id']}}')" id="{{$children['id']}}tutoring-3" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-2"></div>--}}

{{--                                                                                    <div align="left" class="intro-y col-span-12 col-span-3">--}}
{{--                                                                                        <label for="input-wizard-4" class="form-label">Історія України</label>--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-3">--}}
{{--                                                                                        <input disabled onclick="readPrices(`{{$children['id']}}`);valueAdd('{{$children['id']}}')" id="{{$children['id']}}tutoring-4" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">--}}
{{--                                                                                    </div>--}}


{{--                                                                                    <div align="left" class="intro-y col-span-12 col-span-2">--}}
{{--                                                                                        <label for="input-wizard-5" class="form-label">Англійська мова</label>--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-2">--}}
{{--                                                                                        <input disabled onclick="readPrices(`{{$children['id']}}`);valueAdd('{{$children['id']}}')" id="{{$children['id']}}tutoring-5" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-2"></div>--}}

{{--                                                                                    <div align="left" class="intro-y col-span-12 col-span-3">--}}
{{--                                                                                        <label for="input-wizard-6" class="form-label">Біологія</label>--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-3">--}}
{{--                                                                                        <input disabled onclick="readPrices(`{{$children['id']}}`);valueAdd('{{$children['id']}}')" id="{{$children['id']}}tutoring-6" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">--}}
{{--                                                                                    </div>--}}


{{--                                                                                    <div align="left" class="intro-y col-span-12 col-span-2">--}}
{{--                                                                                        <label for="input-wizard-6" class="form-label">Хімія</label>--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-2">--}}
{{--                                                                                        <input disabled onclick="readPrices(`{{$children['id']}}`);valueAdd('{{$children['id']}}')" id="{{$children['id']}}tutoring-7" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-2"></div>--}}

{{--                                                                                    <div align="left" class="intro-y col-span-12 col-span-3">--}}
{{--                                                                                        <label for="input-wizard-6" class="form-label">Географія</label>--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-3">--}}
{{--                                                                                        <input disabled onclick="readPrices(`{{$children['id']}}`);valueAdd('{{$children['id']}}')" id="{{$children['id']}}tutoring-8" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">--}}
{{--                                                                                    </div>--}}


{{--                                                                                    <div align="left" class="intro-y col-span-12 col-span-2">--}}
{{--                                                                                        <label for="input-wizard-6" class="form-label">Фізика</label>--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-2">--}}
{{--                                                                                        <input disabled onclick="readPrices(`{{$children['id']}}`);valueAdd('{{$children['id']}}')" id="{{$children['id']}}tutoring-9" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-2"></div>--}}

{{--                                                                                    <div align="left" class="intro-y col-span-12 col-span-3">--}}

{{--                                                                                    </div>--}}
{{--                                                                                    <div class="intro-y col-span-12 col-span-3">--}}

{{--                                                                                    </div>--}}


{{--                                                                                </div>--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                                <div style="display: none" class="accordion-item">--}}
{{--                                                                    <div id="faq-accordion-content-32-{{$children['id']}}" class="accordion-header">--}}
{{--                                                                        <div align="right" class="grid grid-cols-12 gap-2">--}}
{{--                                                                            <div class="xxl:col-span-5"></div>--}}
{{--                                                                            <div class="col-span-2">--}}
{{--                                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-32-{{$children['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-32-{{$children['id']}}">--}}
{{--                                                                                    <h3 style="width: 100%" class="text-center truncate mr-5">welcome kit</h3>--}}
{{--                                                                                </button>--}}
{{--                                                                            </div>--}}
{{--                                                                            <input onchange="readPrices(`{{$children['id']}}`);" class="col-span-1" name="{{$children['id']}}[additionally][welcome]" id="{{$children['id']}}[additionally][welcome]" type="checkbox">--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                    <div id="faq-accordion-collapse-32-{{$children['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-32-{{$children['id']}}" data-bs-parent="#faq-accordion-30-{{$children['id']}}" style="display: none;">--}}
{{--                                                                        <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">--}}
{{--                                                                            <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">--}}
{{--                                                                                <div class="font-medium text-base"> коробка с мерчем</div>--}}
{{--                                                                                <pre style="height: 15px"></pre>--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                                <div style="display: none" class="accordion-item">--}}
{{--                                                                    <div id="faq-accordion-content-33-{{$children['id']}}" class="accordion-header">--}}
{{--                                                                        <div align="right" class="grid grid-cols-12 gap-2">--}}
{{--                                                                            <div class="xxl:col-span-5"></div>--}}
{{--                                                                            <div class="col-span-2">--}}
{{--                                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-33-{{$children['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-33-{{$children['id']}}">--}}
{{--                                                                                    <h3 style="width: 100%" class="text-center truncate mr-5">Проф-тест</h3>--}}
{{--                                                                                </button>--}}
{{--                                                                            </div>--}}
{{--                                                                            <input onchange="readPrices(`{{$children['id']}}`);" class="col-span-1" id="{{$children['id']}}[additionally][professional]" name="{{$children['id']}}[additionally][professional]" type="checkbox">--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                    <div id="faq-accordion-collapse-33-{{$children['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-33-{{$children['id']}}" data-bs-parent="#faq-accordion-30-{{$children['id']}}" style="display: none;">--}}
{{--                                                                        <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">--}}
{{--                                                                            <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">--}}
{{--                                                                                <div class="font-medium text-base">--}}
{{--                                                                                    - Он-лайн тестування і консультації з профорієнтації для підлітків<br>--}}
{{--                                                                                    - Ваша дитина стане успішною і щасливою, якщо вже сьогодні вона зрозуміє свої сильні сторони, які допоможуть їй в майбутньому самореалізуватися і отримати гідну винагороду в своїй професії.<br>--}}
{{--                                                                                    - Пройти он-лайн тестування на профорієнтацію Career Direct<br>--}}
{{--                                                                                    - Зустрітися з сертифікованим консультантом для обговорення результатів звіту тесту і розуміння як ним користуватися<br>--}}
{{--                                                                                </div>--}}
{{--                                                                                <pre style="height: 15px"></pre>--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

                                            <div class="accordion-item"></div>
                                        </div>
                                </div>

                            @endforeach
                            <div class="mt-3">
                                <label for="months">Кількість місяців до оплати</label>
                                <select onchange="readPrices(`{{$children['id']}}`);" style="background-color: white" class="form-control w-full" name="months" id="months">
                                    <option value="1" selected>1</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                            <div align="center" >
                                <h2 id="actual-price" style="width: 100%">сума до сплати 0 грн</h2>
                            </div>
                                <div align="center"></div>
                                <div align="center">
                                    <div class="text-right mt-5">
                                        <button class="btn btn-warning w-24 mr-1 mb-2">сплатити</button>
                                    </div>
                                </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
@include('js_function')
<script>
    function tabForm(id) {
        @foreach($item as $value)
        document.getElementById('children_{{$value['id']}}').style = 'display: none;';
        @endforeach
        document.getElementById(`children_${id}`).style = '';
    }

    function readPrices(id) {
        sum[id] = 0;
        /*-{{$price['sale']}}-{{$price['referal']}}*/
        if (document.getElementById(id+'[study]').value === '{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['value']}}')     sum[id]=Number(sum[id])+{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['price_actual']}}-Number({{$price['sale']}})-Number({{$price['referal']}});
        if (document.getElementById(id+'[study]').value === '{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['value']}}')    sum[id]=Number(sum[id])+{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['price_actual']}}-Number({{$price['sale']}})-Number({{$price['referal']}});

        /*if (document.getElementById(id+'[supervising]').value==='off')     sum[id]=Number(sum[id]);
        if (document.getElementById(id+'[supervising]').value==='daily')   sum[id]=Number(sum[id])+2000;
        if (document.getElementById(id+'[supervising]').value==='weekly')  sum[id]=Number(sum[id])+2000;
        if (document.getElementById(id+'[supervising]').value==='crash')   sum[id]=Number(sum[id])+2000;

        if (document.getElementById(id+'tutoring-1').checked) sum[id]=Number(sum[id])+300;
        if (document.getElementById(id+'tutoring-2').checked) sum[id]=Number(sum[id])+300;
        if (document.getElementById(id+'tutoring-3').checked) sum[id]=Number(sum[id])+300;
        if (document.getElementById(id+'tutoring-4').checked) sum[id]=Number(sum[id])+300;
        if (document.getElementById(id+'tutoring-5').checked) sum[id]=Number(sum[id])+300;
        if (document.getElementById(id+'tutoring-6').checked) sum[id]=Number(sum[id])+300;
        if (document.getElementById(id+'tutoring-7').checked) sum[id]=Number(sum[id])+300;
        if (document.getElementById(id+'tutoring-8').checked) sum[id]=Number(sum[id])+300;
        if (document.getElementById(id+'tutoring-9').checked) sum[id]=Number(sum[id])+300;

        if (document.getElementById(id+'[additionally][professional]').checked) sum[id]=Number(sum[id])+600;
        if (document.getElementById(id+'[additionally][welcome]').checked) sum[id]=Number(sum[id])+400;

        if (document.getElementById(id+'[study]').value === '{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_TEST]['value']}}') sum[id]=Number(sum[id])+{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_TEST]['price_actual']}}-Number({{$price['referal']}});*/
            //sum[id]=1000;


        let summ = 0;
        for (let numb of sum){
            if (Number.isInteger(numb)){
                console.log(numb);
                summ =Number(summ)+Number(numb);
            }
        }
        summ = document.getElementById('months').value * summ;
        console.log(sum);
        document.getElementById('actual-price').innerHTML = `до сплати ${summ} грн`;
    }
</script>
