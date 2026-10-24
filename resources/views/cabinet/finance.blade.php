<?php
foreach ($services as $key => $child){
    $services[$key]['style']='';
    if (!is_array($child['additionally'])){
        $services[$key]['additionally'] = ['tutoring' => json_encode([0=>false])];
        $services[$key]['style'] = 'display: none';
    }
    if ($child['additionally'] === null){
        $services[$key]['additionally'] = ['x'=>2];
    }
    if (!array_key_exists('tutoring', $services[$key]['additionally'])){
        $services[$key]['additionally']['tutoring'] = null;
    }
}

$tariffs = \App\Helpers\PaymentHelpers::getTariffData();
?>

@extends('../layout/' . $layout)
@section('subhead')
    <title>Новини</title>
@endsection




<style>
    .header-styler {
        background-color: orange;
        padding: 20px;
        border-radius: 25px;
    }

</style>

@section('subcontent')
    <script>var sum = []</script>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center h-10"></div>
                    <div align="center">
                        <div class="intro-y box p-5">
                            <div id="faq-accordion-1" class="accordion p-5">

{{--                                <div class="accordion-item">--}}
{{--                                    <div id="faq-accordion-content-1" class="accordion-header">--}}
{{--                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-1" aria-expanded="false" aria-controls="faq-accordion-collapse-1">--}}
{{--                                            <div class="intro-y col-span-12 sm:col-span-6">--}}
{{--                                                <label for="input-wizard-1" class="text-lg form-label">Підключені послуги</label>--}}
{{--                                                <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>--}}
{{--                                            </div>--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                    <div id="faq-accordion-collapse-1" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-1" data-bs-parent="#faq-accordion-1" style="display: none;">--}}
{{--                                        <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">--}}
{{--                                            <div class="boxed-tabs nav nav-tabs justify-center border border-gray-400 border-dashed text-gray-600 dark:border-gray-700 dark:bg-dark-3 dark:text-gray-500 rounded-md p-1 mt-5 mx-auto" role="tablist">--}}
{{--                                                @foreach($services as $key => $child)--}}
{{--                                                    @if($key===0)--}}
{{--                                                        <a onclick="Tariff.tabForm({{$child['id']}})" data-toggle="tab" data-target="#inactive-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2 active" id="inactive-users-tab" role="tab" aria-selected="true">{{$child['name']}}</a>--}}
{{--                                                    @else--}}
{{--                                                        <a onclick="Tariff.tabForm({{$child['id']}})" data-toggle="tab" data-target="#active-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2" id="active-users-tab" role="tab" aria-controls="active-users" aria-selected="false">{{$child['name']}}</a>--}}
{{--                                                    @endif--}}
{{--                                                @endforeach--}}
{{--                                            </div>--}}
{{--                                            <div style="height: 15px;"></div>--}}
{{--                                            <form method="post" action="{{route('read')}}">--}}
{{--                                                @csrf--}}
{{--                                                <div class="intro-y box p-5">--}}
{{--                                                    <h2 class="font-medium tariff_title">Пакет навчання в JAMM School</h2>--}}

{{--                                                    @foreach($services as $key => $children)--}}
{{--                                                        <script>--}}
{{--                                                            document.addEventListener('DOMContentLoaded', function() {--}}
{{--                                                                window.onload = Tariff.readPrices(`{{$children['id']}}`)--}}
{{--                                                            }, false);--}}
{{--                                                        </script>--}}
{{--                                                        <div id="childDiscount_{{ $children['id'] }}" data-discount="{{ $children['percent_discount'] }}"></div>--}}
{{--                                                        <input type="hidden" value="{{ $children['study'] }}" name="{{ $children['id'] }}[study]" id="study_{{$children['id']}}">--}}
{{--                                                                    <input type="hidden" name="{{$children['id']}}[supervising]"  id="supervising_{{$children['id']}}">--}}
{{--                                                                    <input type="hidden" name="{{$children['id']}}[additionally]" id="additionally_{{$children['id']}}">--}}
{{--                                                        <div id="children_{{$children['id']}}" class="flex flex-col lg:flex-row mt-5">--}}
{{--                                                            @if($key!==0)--}}
{{--                                                                <script>--}}
{{--                                                                    document.getElementById('children_{{$children['id']}}').style = 'display: none;';--}}
{{--                                                                </script>--}}
{{--                                                            @endif--}}
{{--                                                            <div class="intro-y flex-1 box pb-2 mb-5 lg:mb-0 box-tariff">--}}
{{--                                                                <div class="pt-2 pb-14 tariff_1  p-5">--}}
{{--                                                                    <div class="tariff_card_title mt-10">{!! $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['label'] !!}</div>--}}
{{--                                                                    <div class="text-xl font-medium text-center mt-10 fz-36">{{ $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['price_actual'] }} грн</div>--}}
{{--                                                                </div>--}}
{{--                                                                <div class="package-description-container">--}}
{{--                                                                    <ul class="package-description text-center">--}}
{{--                                                                        <li>Вибір швидкості, часу і місця навчання</li>--}}
{{--                                                                        <li>Електронні підручники та навчальні матеріали</li>--}}
{{--                                                                        <li>Записи уроків і тести для перевірки знань</li>--}}
{{--                                                                        <li>Соціалізація</li>--}}
{{--                                                                        <li>Живі вебінари у реальному часі</li>--}}
{{--                                                                        <li>Індивідуальні консультації та онлайн-чати з вчителями</li>--}}
{{--                                                                        <li>Щотижневі зустрічі з кураторами, спілкування з однолітками</li>--}}
{{--                                                                        <li>Документ про навчання державного зразка</li>--}}
{{--                                                                    </ul>--}}
{{--                                                                </div>--}}
{{--                                                                <div class="btn-container">--}}
{{--                                                                    @if($children['study'] === $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['value'])--}}
{{--                                                                        <button data-child-id="{{ $children['id'] }}"--}}
{{--                                                                                data-study="{{ $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['value'] }}"--}}
{{--                                                                                class="btn w-full block btn_change_tariff_active changeTariff fz-14">Для зміни пакету зверніться до клієнт сервісу</button>--}}
{{--                                                                    @else--}}
{{--                                                                        <button data-child-id="{{ $children['id'] }}"--}}
{{--                                                                                data-study="{{ $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['value'] }}"--}}
{{--                                                                                class="btn w-full block btn_change_tariff changeTariff fz-14">Для зміни пакету зверніться до клієнт сервісу</button>--}}
{{--                                                                    @endif--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="intro-y flex-1 box pb-2 mb-5 lg:mb-0 box-tariff">--}}
{{--                                                                <div class="pt-2 pb-14 tariff_2  p-5">--}}
{{--                                                                    <div class="tariff_card_title mt-10">{!! $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['label'] !!}</div>--}}
{{--                                                                    <div class="text-xl font-medium text-center mt-10 fz-36">{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['price_actual']}} грн</div>--}}
{{--                                                                </div>--}}
{{--                                                                <div class="package-description-container">--}}
{{--                                                                    <ul class="package-description text-center">--}}
{{--                                                                        <li>Вибір швидкості, часу і місця навчання</li>--}}
{{--                                                                        <li>Електронні підручники та навчальні матеріали</li>--}}
{{--                                                                        <li>Записи уроків і тести для перевірки знань</li>--}}
{{--                                                                        <li>Соціалізація</li>--}}
{{--                                                                        <li>Живі вебінари у реальному часі</li>--}}
{{--                                                                        <li>Індивідуальні консультації та онлайн-чати з вчителями</li>--}}
{{--                                                                        <li>Щотижневі зустрічі з кураторами, спілкування з однолітками</li>--}}
{{--                                                                        <li>Документ про навчання державного зразка</li>--}}
{{--                                                                        <li>Створення індивідуального плану навчання</li>--}}
{{--                                                                        <li>Супровід супервайзера-куратора</li>--}}
{{--                                                                        <li>Розвиток сильних сторін учня</li>--}}
{{--                                                                        <li>Мотиваційні “живі” зустрічі для розвитку</li>--}}
{{--                                                                    </ul>--}}
{{--                                                                </div>--}}
{{--                                                                <div class="btn-container">--}}
{{--                                                                    @if($children['study'] === $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['value'])--}}
{{--                                                                        <button data-child-id="{{ $children['id'] }}"--}}
{{--                                                                                data-study="{{ $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['value'] }}"--}}
{{--                                                                                class="btn w-full block btn_change_tariff_active changeTariff fz-14">Для зміни пакету зверніться до клієнт сервісу</button>--}}
{{--                                                                    @else--}}
{{--                                                                        <button data-child-id="{{ $children['id'] }}"--}}
{{--                                                                                data-study="{{ $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['value'] }}"--}}
{{--                                                                                class="btn w-full block btn_change_tariff changeTariff fz-14">Для зміни пакету зверніться до клієнт сервісу</button>--}}
{{--                                                                    @endif--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="intro-y flex-1 box pb-2 mb-5 lg:mb-0 box-tariff">--}}
{{--                                                                <div class="pt-2 pb-14 tariff_3  p-5">--}}
{{--                                                                    <div class="tariff_card_title mt-10">«Джеммер за кордоном - українські предмети»</div>--}}
{{--                                                                    <div class="text-xl font-medium text-center mt-10 fz-36">2000 грн</div>--}}
{{--                                                                </div>--}}
{{--                                                                <div class="package-description-container">--}}
{{--                                                                    <ul class="package-description text-center">--}}
{{--                                                                        <li>Вибір швидкості, часу і місця навчання</li>--}}
{{--                                                                        <li>Електронні підручники та навчальні матеріали</li>--}}
{{--                                                                        <li>Записи уроків і тести для перевірки знань</li>--}}
{{--                                                                        <li>Соціалізація</li>--}}
{{--                                                                        <li>Живі вебінари у реальному часі</li>--}}
{{--                                                                        <li>Індивідуальні консультації та онлайн-чати з вчителями</li>--}}
{{--                                                                        <li>Щотижневі зустрічі з кураторами, спілкування з однолітками</li>--}}
{{--                                                                        <li>Документ про навчання державного зразка</li>--}}
{{--                                                                        <li>Перезарахування оцінок з іноземної школи</li>--}}
{{--                                                                    </ul>--}}
{{--                                                                </div>--}}
{{--                                                                <div class="btn-container">--}}
{{--                                                                    <button data-child-id="{{ $children['id'] }}"--}}
{{--                                                                            data-study="new_tariff"--}}
{{--                                                                            class="btn w-full block btn_change_tariff changeTariff fz-14">Для зміни пакету зверніться до клієнт сервісу</button>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    @endforeach--}}

{{--                                                    <div class="mt-10 text-center">--}}
{{--                                                        <a href="https://t.me/JAMMSchool_bot" class="fz-18 tg_bot_link">Телеграм бот - https://t.me/JAMMSchool_bot</a>--}}
{{--                                                    </div>--}}

{{--                                                    <div class="mt-10">--}}
{{--                                                        <label for="months">Кількість місяців до оплати</label>--}}
{{--                                                        <select class="form-control mt-2 w-full changeMonth" name="months" id="months">--}}
{{--                                                            @foreach(range(1, 10) as $month)--}}
{{--                                                                <option @if(auth()->user()->months == $month) selected @endif value="{{ $month }}">{{ $month }}</option>--}}
{{--                                                            @endforeach--}}
{{--                                                        </select>--}}
{{--                                                    </div>--}}
{{--                                                    <hr class="hr_line">--}}
{{--                                                    <div class="tariff_footer">--}}
{{--                                                        <div class="text-left flex-1 tariff_total_sum">--}}
{{--                                                            <span>Всього:</span> <span><span id="actual-price">0</span> грн</span>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="text-right mt-3 flex-1">--}}
{{--                                                            <button class="btn btn-warning p-3 mr-2 mb-2 next_payment">Зберегти зміни</button>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </form>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="accordion-item">--}}
{{--                                    <div id="faq-accordion-content-3" class="accordion-header">--}}
{{--                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-3" aria-expanded="false" aria-controls="faq-accordion-collapse-3">--}}
{{--                                            <div class="intro-y col-span-12 sm:col-span-6">--}}
{{--                                                <label for="input-wizard-1" class="text-lg form-label">Iсторія оплат</label>--}}
{{--                                                <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>--}}
{{--                                            </div>--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                    <div id="faq-accordion-collapse-3" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-3" data-bs-parent="#faq-accordion-1" style="display: none;">--}}
{{--                                        <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">--}}
{{--                                            <div style="height: 15px"></div>--}}
{{--                                            <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">--}}
{{--                                                <div>--}}
{{--                                                    <div style="height: 15px"></div>--}}
{{--                                                    @foreach($payments as $pay)--}}
{{--                                                        <div class="grid grid-cols-12 gap-2">--}}
{{--                                                            <output type="text" class="col-span-4">{{$pay->updated_at}}</output>--}}
{{--                                                            <output type="text" class="col-span-4"><a target="_blank" style="color: #1D68CD" href="https://parent.jammschool.com/cabinet/service?id={{$pay->id}}">Оплата {{$pay->payment_id}}</a></output>--}}
{{--                                                            <output type="text" class="col-span-4">{{$pay->status}}</output>--}}
{{--                                                        </div>--}}
{{--                                                        <div style="height: 15px"></div>--}}
{{--                                                    @endforeach--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="text-center mt-5">
                                    <form action="{{route('user.invoicing')}}">
                                        {{--@dd(Auth::user()->payments->whereMonth('created_at', 9))--}}
                                        <button {{\App\Models\Payment::whereMonth('created_at', date('m'))
                                            ->where('user_id', Auth::id())->get() ? 'disablet' : ''}}
                                                class="btn btn-warning">Оплатити місяць</button>
                                    </form>
                                </div>
                                <div class="accordion-item"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('footer_scripts')
    <script>
        $(document).on('click', '.changeTariff', function (e) {
            e.preventDefault();

            let childId = $(this).data('child-id');
            let study = $(this).data('study');

            $(`.changeTariff[data-child-id=${childId}]`).removeClass('btn_change_tariff_active').addClass('btn_change_tariff');
            $(this).addClass('btn_change_tariff_active');
            window.open('https://t.me/JAMMSchool_bot');

            $(`#study_${childId}`).val(study);
            Tariff.readPrices(childId);
        });

        $(document).on('change', '.changeMonth', function (e) {
            e.preventDefault();

            let childId = $(this).data('child-id');
            Tariff.readPrices(childId);
        });

        const Tariff = {
            readPrices: function (id) {
                sum[id] = 0;
                let childDiscount = $('#childDiscount_'+id).data('discount');
                let tariffLearner = {{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['price_actual']}};
                let tariffListener = {{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['price_actual']}};
                let newTariff = 2000;
                let study = $('#study_'+id).val();
                if (study === '{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['value']}}') {
                    sum[id] = Number(sum[id]) + tariffLearner - Number({{$price['sale']}})-Number({{$price['referal']}}) - this.getPercentDiscount(tariffLearner, childDiscount);
                }

                if (study === '{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['value']}}') {
                    sum[id] = Number(sum[id]) + tariffListener - Number({{$price['sale']}})-Number({{$price['referal']}}) - this.getPercentDiscount(tariffListener, childDiscount);
                }

                if (study === 'new_tariff') {
                    sum[id] = Number(sum[id]) + newTariff - Number({{$price['sale']}})-Number({{$price['referal']}}) - this.getPercentDiscount(newTariff, childDiscount);
                }

                let totalSum = 0;
                for (let numb of sum){
                    if (Number.isInteger(numb)){
                        totalSum = Number(totalSum) + Number(numb);
                    }
                }
                totalSum = document.getElementById('months').value * totalSum;
                document.getElementById('actual-price').innerHTML = totalSum;
            },
            getPercentDiscount: function (tariff, percent) {
                if (percent > 0) {
                    return (percent * tariff / 100).toFixed(0);
                }

                return 0;
            },
            tabForm: function (id) {
                @foreach($services as $value)
                    document.getElementById('children_{{$value['id']}}').style = 'display: none;';
                @endforeach
                document.getElementById(`children_${id}`).style = '';
            }
        }
    </script>
@endpush
