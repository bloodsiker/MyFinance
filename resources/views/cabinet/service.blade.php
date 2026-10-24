<?php
foreach ($services as $key => $child){
    $services[$key]['style']='';
    if (!is_array($child['additionally'])){
        $services[$key]['additionally'] = ['tutoring' => json_encode([0=>false])];
        $services[$key]['style'] = 'display: none';
    }
}
?>
<style>
    .header-styler {
        background-color: orange;
        padding: 20px;
        border-radius: 25px;
    }

</style>


@extends('../layout/' . $layout)

@section('subhead')
    <title>Новини</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center h-10"></div>
                    <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                        <div style="width: 100%;" class="intro-y box">
                            <div style="box-shadow: 0 0 10px rgba(0,0,0,0.5);width: 100%;" class="intro-y box">
                                <div align="center">
                                    <div class="boxed-tabs nav nav-tabs justify-center border border-gray-400 border-dashed text-gray-600 dark:border-gray-700 dark:bg-dark-3 dark:text-gray-500 rounded-md p-1 mt-5 mx-auto" role="tablist">
                                        @foreach($services as $key => $child)
                                            @if($key===0)
                                                <a onclick="tabForm({{$child['id']}})" data-toggle="tab" data-target="#inactive-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2 active" id="inactive-users-tab" role="tab" aria-selected="true">{{$child['name']}}</a>
                                            @else
                                                <a onclick="tabForm({{$child['id']}})" data-toggle="tab" data-target="#active-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2" id="active-users-tab" role="tab" aria-controls="active-users" aria-selected="false">{{$child['name']}}</a>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div style="height: 15px;"></div>

                                    @foreach($services as $key => $child)
                                        @php
                                        $res = json_decode($child['additionally']['tutoring']);
                                        @endphp
                                        <div id="children_{{$child['id']}}">
                                            @if($key!==0)
                                                <script>
                                                    document.getElementById('children_{{$child['id']}}').style = 'display: none;';
                                                </script>
                                            @endif
                                                <div id="faq-accordion-1" class="accordion p-5">


                                                    <div class="accordion-item">
                                                        <div id="faq-accordion-content-1" class="accordion-header">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-1" aria-expanded="false" aria-controls="faq-accordion-collapse-1">
                                                                <div class="intro-y col-span-12 sm:col-span-6">
                                                                    <label for="input-wizard-1" class="text-lg form-label">Оплачені послуги</label>
                                                                    <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                                </div>
                                                            </button>
                                                        </div>
                                                        <div id="faq-accordion-collapse-1" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-1" data-bs-parent="#faq-accordion-1" style="display: none;">
                                                            <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                <div>
                                                                    <div id="faq-accordion-10-{{$child['id']}}" class="accordion p-5">

                                                                        <div id="ac-item-1" class="accordion-item">
                                                                            <label for="ac-item-1">
                                                                                <h3 class="header-styler" style="text-align: left; margin-bottom: 20px; font-weight: 700; font-size:4vmin" class="truncate mr-5">«Учень» <br> + живі уроки <br> + соціалізація</h3>
                                                                                </label>
                                                                            <div id="faq-accordion-content-11-{{$child['id']}}" class="accordion-header">

                                                                                <div align="right" class="grid grid-cols-12 gap-2">
                                                                                    <div class="col-span-4"></div>
                                                                                    <div class="col-span-4">
                                                                                        <button class="accordion-button collapsed-{{$child['id']}}" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-11-{{$child['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-11-{{$child['id']}}">
                                                                                            Обрати
                                                                                        </button>
                                                                                    </div>
                                                                                    <input disabled {{$child['study']==='listener'?'checked':''}} class="show-code col-span-2 form-check-switch mr-0 ml-3" id="study" name="{{$child['id']}}[study]" value="listener" type="radio">
                                                                                </div>
                                                                            </div>
                                                                            <div align="left" id="faq-accordion-collapse-11-{{$child['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-11-{{$child['id']}}" data-bs-parent="#faq-accordion-10-{{$child['id']}}" style="display: none;">
                                                                                <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                                    <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">
                                                                                        <div class="intro-y col-span-12 col-span-3"></div>
                                                                                        <div class="intro-y col-span-12 xl:col-span-9"><h3>
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
                                                                                    <h1 style="font-size: 30px; font-weight: 700">2900 грн <p style="text-decoration: line-through; font-size: 20px;">замість 3520 грн<p> </h1>

                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div id="ac-item-2" class="accordion-item">
                                                                            <label for="ac-item-2" >
                                                                                <h3 class = "header-styler" style="text-align: left; margin-bottom: 20px; font-weight: 700; font-size:4vmin" style="width: 100%" class="truncate mr-5">«Учень» <br> + живі уроки <br> + соціалізація <br> + особистий куратор</h3>

                                                                            </label>
                                                                            <div id="faq-accordion-content-12-{{$child['id']}}" class="accordion-header">

                                                                                <div class="grid grid-cols-12 gap-2">
                                                                                    <div class="col-span-4"></div>
                                                                                    <div class="col-span-4">
                                                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-12-{{$child['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-12-{{$child['id']}}">
                                                                                           Обрати
                                                                                        </button>
                                                                                    </div>
                                                                                    <input disabled {{$child['study']==='learner'?'checked':''}} class="show-code col-span-2 form-check-switch mr-0 ml-3" name="{{$child['id']}}[study]" id="study" value="learner" type="radio">
                                                                                </div>

                                                                            </div>

                                                                            <div align="left" id="faq-accordion-collapse-12-{{$child['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-12-{{$child['id']}}" data-bs-parent="#faq-accordion-10-{{$child['id']}}" style="display: none;">
                                                                                <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                                    <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">
                                                                                        <div class="intro-y col-span-12 col-span-3"></div>
                                                                                        <div class="intro-y col-span-12 xl:col-span-9"><h3>
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
                                                                                </div>
                                                                                <div style="padding:20px; border: 5px solid ; border-radius:25px; margin:20px">
                                                                                    <h1 style="font-size: 30px; font-weight: 700">4900 грн <p style="text-decoration: line-through; font-size: 20px;">замість 5760 грн<p> </h1>

                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div id="ac-item-3" class="accordion-item">
                                                                            <label for="ac-item-3">
                                                                                <h3  class = "header-styler" style="text-align: left;  margin-bottom: 20px; font-weight: 700; font-size:4vmin" style="width: 100%" class="truncate mr-5">«Учень»</h3>

                                                                            </label>
                                                                            <div id="faq-accordion-content-13-{{$child['id']}}" class="accordion-header">


                                                                                <div class="grid grid-cols-12 gap-2">
                                                                                    <div class="col-span-4"></div>
                                                                                    <div class="col-span-4">
                                                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-13-{{$child['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-13-{{$child['id']}}">
                                                                                           Обрати
                                                                                        </button>
                                                                                    </div>
                                                                                    <input disabled {{$child['study']==='test'?'checked':'disabled'}} class="show-code col-span-2 form-check-switch mr-0 ml-3" name="{{$child['id']}}[study]" id="study" value="test" type="radio">
                                                                                </div>

                                                                            </div>

                                                                            <div align="left" id="faq-accordion-collapse-13-{{$child['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-13-{{$child['id']}}" data-bs-parent="#faq-accordion-10-{{$child['id']}}" style="display: none;">
                                                                                <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                                    <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">
                                                                                        <div class="intro-y col-span-12 col-span-3"></div>
                                                                                        <div class="intro-y col-span-12 xl:col-span-9"><h3>
                                                                                            <ul>
                                                                                                <li>&#10003; Вибір швидкості, часу і місця навчання</li>
                                                                                                <li>&#10003; Електронні підручники та навчальні матеріали</li>
                                                                                                <li>&#10003; Записи уроків і тести для перевірки знань</li>
                                                                                                <li>&#10003; Можливість отримати виписку за результатами навчання</li>
                                                                                                <li>&#10003; Соціалізація – “Уроки Єдності”</li>
                                                                                                </ul>
                                                                                            </h3>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="accordion-item">
                                                        <div id="faq-accordion-content-2" class="accordion-header">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-2" aria-expanded="false" aria-controls="faq-accordion-collapse-2">
                                                                <div class="intro-y col-span-12 sm:col-span-6">
                                                                    <label for="input-wizard-1" class="text-lg form-label">Додаткові послуги</label>
                                                                    <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                                </div>
                                                            </button>
                                                        </div>


                                                        <div id="faq-accordion-collapse-2" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-2" data-bs-parent="#faq-accordion-1" style="display: none;">
                                                            <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                    <div>
                                                                        <div id="faq-accordion-20-{{$child['id']}}" class="accordion p-4">

                                                                            <div id="ad-ac-item1" class="accordion-item">
                                                                                <label for="ad-ac-item1" style="align-content: left">
                                                                                    <h3  class = "header-styler" style="text-align: left; margin-bottom: 20px; font-weight: 700; font-size:4vmin" class="truncate mr-5">Відсутнiй</h3>

                                                                                </label>
                                                                                <div id="faq-accordion-content-21-{{$child['id']}}" class="accordion-header">


                                                                                    <div class="grid grid-cols-12 gap-2">
                                                                                        <div class="col-span-4"></div>
                                                                                        <div class="col-span-4">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-21-{{$child['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-21-{{$child['id']}}">
                                                                                                Відключити послугу
                                                                                            </button>
                                                                                        </div>
                                                                                        <input disabled {{$child['supervising']==='off'?'checked':''}} class="show-code col-span-2 form-check-switch mr-0 ml-3" id="{{$child['id']}}[supervising]" name="{{$child['id']}}[supervising]" value="off" type="radio">
                                                                                    </div>
                                                                                </div>

                                                                                <div align="left" id="faq-accordion-collapse-21-{{$child['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-21-{{$child['id']}}" data-bs-parent="#faq-accordion-20-{{$child['id']}}" style="display: none;">
                                                                                    <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                                        <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">
                                                                                            <div class="intro-y col-span-12 col-span-3"></div>
                                                                                            <div class="intro-y col-span-12 xl:col-span-9"><h3>

                                                                                                </h3>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>

                                                                            <div class="accordion-item" style = "display:none">
                                                                                <div id="faq-accordion-content-21-{{$child['id']}}" class="accordion-header">


                                                                                    <div class="grid grid-cols-12 gap-2">
                                                                                        <div class="col-span-4"></div>
                                                                                        <div class="col-span-4">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-21-{{$child['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-21-{{$child['id']}}">
                                                                                                <h3 style="width: 100%" class="truncate mr-5">Щоденний</h3>
                                                                                            </button>
                                                                                        </div>
                                                                                        <input disabled {{$child['supervising']==='daily'?'checked':''}} class="show-code col-span-2 form-check-switch mr-0 ml-3" name="{{$child['id']}}[supervising]" id="supervising" value="daily" type="radio">
                                                                                    </div>
                                                                                </div>

                                                                                <div align="left" id="faq-accordion-collapse-21-{{$child['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-21-{{$child['id']}}" data-bs-parent="#faq-accordion-20-{{$child['id']}}" style="display: none;">
                                                                                    <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                                        <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">
                                                                                            <div class="intro-y col-span-12 col-span-3"></div>
                                                                                            <div class="intro-y col-span-12 xl:col-span-9"><h3>
                                                                                                    - Щоденні зустрічі групи учнів з супервайзером<br>
                                                                                                    - Постановка цілей на тиждень<br>
                                                                                                    - Щоденний моніторинг процесу навчання, розвиток самостійності<br>
                                                                                                    - Щотижневий звіт супервайзеру про досягнення цілей<br>
                                                                                                </h3>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div id="ad-ac-item-2" class="accordion-item">
                                                                                <label for="ad-ac-item-2" style="align-content: center">
                                                                                    <h3 class = "header-styler" style="text-align: left; margin-bottom:20px; font-weight: 700; font-size:4vmin" style="width: 100%" class="truncate mr-5">Супровід супервайзера-куратора</h3>
                                                                                    </label>
                                                                                <div id="faq-accordion-content-22-{{$child['id']}}" class="accordion-header">

                                                                                    <div class="grid grid-cols-12 gap-2">
                                                                                        <div class="col-span-4"></div>
                                                                                        <div class="col-span-4">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-22-{{$child['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-22-{{$child['id']}}">
                                                                                                Обрати
                                                                                            </button>
                                                                                        </div>
                                                                                        <input disabled {{$child['supervising']==='weekly'?'checked':''}} class="show-code col-span-2 form-check-switch mr-0 ml-3" name="{{$child['id']}}[supervising]" id="supervising" value="weekly" type="radio">
                                                                                    </div>

                                                                                </div>

                                                                                <div align="left" id="faq-accordion-collapse-22-{{$child['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-22-{{$child['id']}}" data-bs-parent="#faq-accordion-20-{{$child['id']}}" style="display: none;">
                                                                                    <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                                        <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">
                                                                                            <div class="intro-y col-span-12 col-span-3"></div>
                                                                                            <div class="intro-y col-span-12 xl:col-span-9"><h3>
                                                                                                <ul>
                                                                                                    <li> &#10003; Створення індивідуального плану навчання </li>
                                                                                                    <li> &#10003; Супровід супервайзера-куратора </li>
                                                                                                    <li> &#10003; Розвиток сильних сторін учня </li>
                                                                                                    <li> &#10003; Мотиваційні «живі» зустрічі для розвитку навичок самостійності і самодисципліни </li>
                                                                                                    </ul>
                                                                                                </h3>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div style="padding:20px; border: 5px solid ; border-radius:25px; margin:20px">
                                                                                        <h1 style="font-size: 30px; font-weight: 700">2000 грн</h1>

                                                                                    </div>
                                                                                </div>

                                                                            </div>

                                                                            <div class="accordion-item" style = "display:none">
                                                                                <div id="faq-accordion-content-23-{{$child['id']}}" class="accordion-header">

                                                                                    <div class="grid grid-cols-12 gap-2">
                                                                                        <div class="col-span-4"></div>
                                                                                        <div class="col-span-4">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-22-{{$child['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-22-{{$child['id']}}">
                                                                                                <h3 style="width: 100%" class="truncate mr-5">Креш-курс</h3>
                                                                                            </button>
                                                                                        </div>
                                                                                        <input disabled {{$child['supervising']==='weekly'?'crash':''}} class="show-code col-span-2 form-check-switch mr-0 ml-3" name="{{$child['id']}}[supervising]" id="supervising" value="crash" type="radio">
                                                                                    </div>

                                                                                </div>

                                                                                <div align="left" id="faq-accordion-collapse-23-{{$child['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-23-{{$child['id']}}" data-bs-parent="#faq-accordion-20-{{$child['id']}}" style="display: none;">
                                                                                    <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                                        <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">
                                                                                            <div class="intro-y col-span-12 col-span-3"></div>
                                                                                            <div class="intro-y col-span-12 xl:col-span-9"><h3>
                                                                                                    - Онбординг учня<br>
                                                                                                    - Ознайомлення з відмінностями JS від інших шкіл<br>
                                                                                                    - Формування базових навичок роботи з нашою навчальною онлайн платформою<br>
                                                                                                    - Формування базового розкладу<br>
                                                                                                </h3>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{--<div class="accordion-item">
                                                        <div id="faq-accordion-content-3" class="accordion-header">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-3" aria-expanded="false" aria-controls="faq-accordion-collapse-3">
                                                                <div class="intro-y col-span-12 sm:col-span-6">
                                                                    <label for="input-wizard-1" class="text-lg form-label">Додатково</label>
                                                                    <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                                </div>
                                                            </button>
                                                        </div>
                                                        <div id="faq-accordion-collapse-3" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-3" data-bs-parent="#faq-accordion-1" style="display: none;">
                                                            <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                <div>

                                                                    <div id="faq-accordion-30-{{$child['id']}}" class="accordion p-5">
                                                                        <div class="accordion-item">
                                                                            <div id="faq-accordion-content-31-{{$child['id']}}" class="accordion-header">
                                                                                <div align="right" class="grid grid-cols-12 gap-2">
                                                                                    <div class="xxl:col-span-5"></div>
                                                                                    <div class="col-span-2">
                                                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-31-{{$child['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-31-{{$child['id']}}">
                                                                                            <h3 style="width: 100%" class="text-center truncate mr-5">Підготовка до ДПА/ЗНО<br>старша школа</h3>
                                                                                        </button>
                                                                                    </div>
                                                                                    @if(key($child['additionally'])==='tutoring'&&array_search(true, $res)!==false&&json_decode($child['additionally']['tutoring'])!==null)
                                                                                        <script>
                                                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                                                window.onload = blockMark(`{{$child['id']}}`)
                                                                                            }, false);
                                                                                        </script>
                                                                                    @endif
                                                                                    <input disabled {{key($child['additionally'])==='tutoring'&&array_search(true, $res)!==false&&json_decode($child['additionally']['tutoring'])!==null?'checked':''}} onclick="blockMark('{{$child['id']}}');" class="show-code form-check-switch mr-0 ml-3" id="{{$child['id']}}[att]" name="{{$child['id']}}[additionally][tutoring]" type="checkbox">
                                                                                </div>
                                                                            </div>
                                                                            <div id="faq-accordion-collapse-31-{{$child['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-31-{{$child['id']}}" data-bs-parent="#faq-accordion-30-{{$child['id']}}" style="display: none;">
                                                                                <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                                    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
                                                                                        <div class="font-medium text-base">
                                                                                            Хочеш вивчати предмети глибше, швидше обирай репетитора і стартуй поглиблене навчання.<br>
                                                                                            ( після вибору предметів, менеджер зв'яжеться з Вами для вибору часу для додаткових зустрічей з вчителем )<br>
                                                                                        </div>
                                                                                        <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">

                                                                                            <div align="left" class="intro-y col-span-12 col-span-2">
                                                                                                <label for="input-wizard-1" class="form-label">Алгебра</label>
                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-2">
                                                                                                <input disabled {{key($child['additionally'])==='tutoring'?
                                                                                                                            current($res)?
                                                                                                                                'checked':
                                                                                                                                '':
                                                                                                                            ''}} disabled onclick="valueAdd('{{$child['id']}}')" id="{{$child['id']}}tutoring-1" name="" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">
                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-2"></div>

                                                                                            <div align="left" class="intro-y col-span-12 col-span-3">
                                                                                                <label for="input-wizard-2" class="form-label">Геометрія</label>
                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-3">
                                                                                                <input disabled {{key($child['additionally'])==='tutoring'?
                                                                                                                            next($res)?
                                                                                                                                'checked':
                                                                                                                                '':
                                                                                                                            ''}} disabled onclick="valueAdd('{{$child['id']}}')" id="{{$child['id']}}tutoring-2" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">
                                                                                            </div>


                                                                                            <div align="left" class="intro-y col-span-12 col-span-2">
                                                                                                <label for="input-wizard-3" class="form-label"> Українська мова/література</label>
                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-2">
                                                                                                <input disabled {{key($child['additionally'])==='tutoring'?
                                                                                                                            next($res)?
                                                                                                                                'checked':
                                                                                                                                '':
                                                                                                                            ''}} disabled onclick="valueAdd('{{$child['id']}}')" id="{{$child['id']}}tutoring-3" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">
                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-2"></div>

                                                                                            <div align="left" class="intro-y col-span-12 col-span-3">
                                                                                                <label for="input-wizard-4" class="form-label">Істория України</label>
                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-3">
                                                                                                <input disabled {{key($child['additionally'])==='tutoring'?
                                                                                                                            next($res)?
                                                                                                                                'checked':
                                                                                                                                '':
                                                                                                                            ''}} disabled onclick="valueAdd('{{$child['id']}}')" id="{{$child['id']}}tutoring-4" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">
                                                                                            </div>



                                                                                            <div align="left" class="intro-y col-span-12 col-span-2">
                                                                                                <label for="input-wizard-5" class="form-label">Англійська мова</label>
                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-2">
                                                                                                <input disabled {{key($child['additionally'])==='tutoring'?
                                                                                                                            next($res)?
                                                                                                                                'checked':
                                                                                                                                '':
                                                                                                                            ''}} disabled onclick="valueAdd('{{$child['id']}}')" id="{{$child['id']}}tutoring-5" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">
                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-2"></div>

                                                                                            <div align="left" class="intro-y col-span-12 col-span-3">
                                                                                                <label for="input-wizard-6" class="form-label">Біологія</label>
                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-3">
                                                                                                <input disabled {{key($child['additionally'])==='tutoring'?
                                                                                                                            next($res)?
                                                                                                                                'checked':
                                                                                                                                '':
                                                                                                                            ''}} disabled onclick="valueAdd('{{$child['id']}}')" id="{{$child['id']}}tutoring-6" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">
                                                                                            </div>


                                                                                            <div align="left" class="intro-y col-span-12 col-span-2">
                                                                                                <label for="input-wizard-6" class="form-label">Хімія</label>
                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-2">
                                                                                                <input disabled {{key($child['additionally'])==='tutoring'?
                                                                                                                            next($res)?
                                                                                                                                'checked':
                                                                                                                                '':
                                                                                                                            ''}} disabled onclick="valueAdd('{{$child['id']}}')" id="{{$child['id']}}tutoring-7" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">
                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-2"></div>

                                                                                            <div align="left" class="intro-y col-span-12 col-span-3">
                                                                                                <label for="input-wizard-6" class="form-label">Географія</label>
                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-3">
                                                                                                <input disabled {{key($child['additionally'])==='tutoring'?
                                                                                                                            next($res)?
                                                                                                                                'checked':
                                                                                                                                '':
                                                                                                                            ''}} disabled onclick="valueAdd('{{$child['id']}}')" id="{{$child['id']}}tutoring-8" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">
                                                                                            </div>

                                                                                            <div align="left" class="intro-y col-span-12 col-span-2">
                                                                                                <label for="input-wizard-6" class="form-label">Фізика</label>
                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-2">
                                                                                                <input disabled {{key($child['additionally'])==='tutoring'?
                                                                                                                            next($res)?
                                                                                                                                'checked':
                                                                                                                                '':
                                                                                                                            ''}} disabled onclick="valueAdd('{{$child['id']}}')" id="{{$child['id']}}tutoring-9" data-target="#on-click-tooltip" class="show-code form-check-switch mr-0 ml-3" type="checkbox">
                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-2"></div>

                                                                                            <div align="left" class="intro-y col-span-12 col-span-3">

                                                                                            </div>
                                                                                            <div class="intro-y col-span-12 col-span-3">

                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        --}}{{--<div class="accordion-item">
                                                                            <div id="faq-accordion-content-32-{{$child['id']}}" class="accordion-header">
                                                                                <div align="right" class="grid grid-cols-12 gap-2">
                                                                                    <div class="xxl:col-span-5"></div>
                                                                                    <div class="col-span-2">
                                                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-32-{{$child['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-32-{{$child['id']}}">
                                                                                            <h3 style="width: 100%" class="text-center truncate mr-5">welcome kit</h3>
                                                                                        </button>
                                                                                    </div>
                                                                                    <input disabled {{array_key_exists('welcome', $child['additionally'])&&
                                                                                                            $child['additionally']['welcome']==='on'?'checked':''}} class="show-code form-check-switch mr-0 ml-3" name="{{$child['id']}}[additionally][welcome]" id="{{$child['id']}}[additionally][welcome]" type="checkbox">
                                                                                </div>
                                                                            </div>
                                                                            <div id="faq-accordion-collapse-32-{{$child['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-32-{{$child['id']}}" data-bs-parent="#faq-accordion-30-{{$child['id']}}" style="display: none;">
                                                                                <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                                    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
                                                                                        <div class="font-medium text-base"> коробка с мерчем</div>
                                                                                        <pre style="height: 15px"></pre>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="accordion-item">
                                                                            <div id="faq-accordion-content-33-{{$child['id']}}" class="accordion-header">
                                                                                <div align="right" class="grid grid-cols-12 gap-2">
                                                                                    <div class="xxl:col-span-5"></div>
                                                                                    <div class="col-span-2">
                                                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-33-{{$child['id']}}" aria-expanded="false" aria-controls="faq-accordion-collapse-33-{{$child['id']}}">
                                                                                            <h3 style="width: 100%" class="text-center truncate mr-5">Проф-тест</h3>
                                                                                        </button>
                                                                                    </div>
                                                                                    <input disabled {{array_key_exists('professional', $child['additionally'])&&$child['additionally']['professional']==='on'?'checked':''}} class="show-code form-check-switch mr-0 ml-3" id="{{$child['id']}}[additionally][professional]" name="{{$child['id']}}[additionally][professional]" type="checkbox">
                                                                                </div>
                                                                            </div>
                                                                            <div id="faq-accordion-collapse-33-{{$child['id']}}" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-33-{{$child['id']}}" data-bs-parent="#faq-accordion-30-{{$child['id']}}" style="display: none;">
                                                                                <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                                                    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
                                                                                        <div class="font-medium text-base">
                                                                                            - Он-лайн тестування і консультації з профорієнтації для підлітків<br>
                                                                                            - Ваша дитина стане успішною і щасливою, якщо вже сьогодні вона зрозуміє свої сильні сторони, які допоможуть їй в майбутньому самореалізуватися і отримати гідну винагороду в своїй професії.<br>
                                                                                            - Пройти он-лайн тестування на профорієнтацію Career Direct<br>
                                                                                            - Зустрітися з сертифікованим консультантом для обговорення результатів звіту тесту і розуміння як ним користуватися<br>
                                                                                        </div>
                                                                                        <pre style="height: 15px"></pre>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>--}}{{--
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>--}}

                                                    <div style="height: 15px;"></div>


                                                </div>
                                        </div>
                                    @endforeach
                                    <div style="height: 25px"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


<script>
    function tabForm(id) {
        @foreach($services as $value)
        document.getElementById('children_{{$value['id']}}').style = 'display: none;';
        @endforeach
        document.getElementById(`children_${id}`).style = '';
    }
</script>

