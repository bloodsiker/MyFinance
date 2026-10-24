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
                                <a onclick="Tariff.tabForm({{$children['id']}})" data-toggle="tab" data-target="#inactive-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2 active" id="inactive-users-tab" role="tab" aria-selected="true">{{$children['name']}}</a>
                            @else
                                <a onclick="Tariff.tabForm({{$children['id']}})" data-toggle="tab" data-target="#active-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2" id="active-users-tab" role="tab" aria-controls="active-users" aria-selected="false">{{$children['name']}}</a>
                            @endif
                        @endforeach
                    </div>
                    <div class="intro-y flex items-center h-10"></div>

                    <form method="post" action="{{route('second_step')}}">
                        @csrf
                        <div class="intro-y box p-5">
{{--                            <h2 class="font-medium tariff_title">Пакет навчання в JAMM School</h2>--}}

                            @foreach($item as $key => $children)
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        window.onload = Tariff.readPrices(`{{$children['id']}}`)
                                    }, false);
                                </script>
                                <div id="childDiscount_{{ $children['id'] }}" data-discount="{{ $children['percent_discount'] }}"></div>
                                <input type="hidden" value="{{ $children['study'] }}" name="{{ $children['id'] }}[study]" id="study_{{$children['id']}}">
                                <input type="hidden" name="{{$children['id']}}[supervising]"  id="supervising_{{$children['id']}}">
                                <input type="hidden" name="{{$children['id']}}[additionally]" id="additionally_{{$children['id']}}">
{{--                                <div id="children_{{$children['id']}}" class="flex flex-col lg:flex-row mt-5">--}}
{{--                                    @if($key!==0)--}}
{{--                                        <script>--}}
{{--                                            document.getElementById('children_{{$children['id']}}').style = 'display: none;';--}}
{{--                                        </script>--}}
{{--                                    @endif--}}
{{--                                        <div class="intro-y flex-1 box pb-2 mb-5 lg:mb-0 box-tariff">--}}
{{--                                            <div class="pt-2 pb-14 tariff_1 p-5">--}}
{{--                                                <div class="tariff_card_title mt-10">{!! $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['label'] !!}</div>--}}
{{--                                                <div class="text-xl font-medium text-center mt-10 fz-36">{{ $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['price_actual'] }} грн</div>--}}
{{--                                            </div>--}}
{{--                                            <div class="package-description-container">--}}
{{--                                                <ul class="package-description text-center">--}}
{{--                                                    <li>Вибір швидкості, часу і місця навчання</li>--}}
{{--                                                    <li>Електронні підручники та навчальні матеріали</li>--}}
{{--                                                    <li>Записи уроків і тести для перевірки знань</li>--}}
{{--                                                    <li>Соціалізація</li>--}}
{{--                                                    <li>Живі вебінари у реальному часі</li>--}}
{{--                                                    <li>Індивідуальні консультації та онлайн-чати з вчителями</li>--}}
{{--                                                    <li>Щотижневі зустрічі з кураторами, спілкування з однолітками</li>--}}
{{--                                                    <li>Документ про навчання державного зразка</li>--}}
{{--                                                </ul>--}}
{{--                                            </div>--}}
{{--                                            <div class="btn-container">--}}
{{--                                                @if($children['study'] === $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['value'])--}}
{{--                                                    <button data-child-id="{{ $children['id'] }}"--}}
{{--                                                            data-study="{{ $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['value'] }}"--}}
{{--                                                            class="btn w-full block btn_change_tariff_active changeTariff">Обраний пакет</button>--}}
{{--                                                @else--}}
{{--                                                    <button data-child-id="{{ $children['id'] }}"--}}
{{--                                                            data-study="{{ $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['value'] }}"--}}
{{--                                                            class="btn w-full block btn_change_tariff changeTariff">Обрати план</button>--}}
{{--                                                @endif--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="intro-y flex-1 box pb-2 mb-5 lg:mb-0 box-tariff">--}}
{{--                                            <div class="pt-2 pb-14 tariff_2 p-5">--}}
{{--                                                <div class="tariff_card_title mt-10">{!! $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['label'] !!}</div>--}}
{{--                                                <div class="text-xl font-medium text-center mt-10 fz-36">{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['price_actual']}} грн</div>--}}
{{--                                            </div>--}}
{{--                                            <div class="package-description-container">--}}
{{--                                                <ul class="package-description text-center">--}}
{{--                                                    <li>Вибір швидкості, часу і місця навчання</li>--}}
{{--                                                    <li>Електронні підручники та навчальні матеріали</li>--}}
{{--                                                    <li>Записи уроків і тести для перевірки знань</li>--}}
{{--                                                    <li>Соціалізація</li>--}}
{{--                                                    <li>Живі вебінари у реальному часі</li>--}}
{{--                                                    <li>Індивідуальні консультації та онлайн-чати з вчителями</li>--}}
{{--                                                    <li>Щотижневі зустрічі з кураторами, спілкування з однолітками</li>--}}
{{--                                                    <li>Документ про навчання державного зразка</li>--}}
{{--                                                    <li>Створення індивідуального плану навчання</li>--}}
{{--                                                    <li>Супровід супервайзера-куратора</li>--}}
{{--                                                    <li>Розвиток сильних сторін учня</li>--}}
{{--                                                    <li>Мотиваційні “живі” зустрічі для розвитку</li>--}}
{{--                                                </ul>--}}
{{--                                            </div>--}}
{{--                                            <div class="btn-container">--}}
{{--                                                @if($children['study'] === $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['value'])--}}
{{--                                                    <button data-child-id="{{ $children['id'] }}"--}}
{{--                                                            data-study="{{ $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['value'] }}"--}}
{{--                                                            class="btn w-full block btn_change_tariff_active changeTariff">Обраний пакет</button>--}}
{{--                                                @else--}}
{{--                                                    <button data-child-id="{{ $children['id'] }}"--}}
{{--                                                            data-study="{{ $tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['value'] }}"--}}
{{--                                                            class="btn w-full block btn_change_tariff changeTariff">Обрати план</button>--}}
{{--                                                @endif--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="intro-y flex-1 box pb-2 mb-5 lg:mb-0 box-tariff">--}}
{{--                                            <div class="pt-2 pb-14 tariff_3 p-5">--}}
{{--                                                <div class="tariff_card_title mt-10">«Джеммер за кордоном - українські предмети»</div>--}}
{{--                                                <div class="text-xl font-medium text-center mt-10 fz-36">2000 грн</div>--}}
{{--                                            </div>--}}
{{--                                            <div class="package-description-container">--}}
{{--                                                <ul class="package-description text-center">--}}
{{--                                                    <li>Перезарахування оцінок з іноземної школи</li>--}}
{{--                                                    <li>Вибір швидкості, часу і місця навчання</li>--}}
{{--                                                    <li>Електронні підручники та навчальні матеріали</li>--}}
{{--                                                    <li>Записи уроків і тести для перевірки знань</li>--}}
{{--                                                    <li>Соціалізація</li>--}}
{{--                                                    <li>Живі вебінари у реальному часі</li>--}}
{{--                                                    <li>Індивідуальні консультації та онлайн-чати з вчителями</li>--}}
{{--                                                    <li>Щотижневі зустрічі з кураторами, спілкування з однолітками</li>--}}
{{--                                                    <li>Документ про навчання державного зразка</li>--}}
{{--                                                    <li>Перезарахування оцінок з іноземної школи</li>--}}
{{--                                                </ul>--}}
{{--                                            </div>--}}
{{--                                            <div class="btn-container">--}}
{{--                                                <button data-child-id="{{ $children['id'] }}"--}}
{{--                                                        disabled--}}
{{--                                                        class="btn w-full block btn_change_tariff changeTariff disabled">Обрати план</button>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                </div>--}}
                            @endforeach

                            <div class="mt-10">
                                <label for="months">Кількість місяців до оплати</label>
                                <select class="form-control mt-2 w-full changeMonth" name="months" id="months">
                                    @foreach(range(1, 10) as $month)
                                        <option @if(auth()->user()->months == $month) selected @endif value="{{ $month }}">{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <hr class="hr_line">
                            <div class="tariff_footer">
                                <div class="text-left flex-1 tariff_total_sum">
                                    <span>Всього:</span> <span><span id="actual-price">0</span> грн</span>
                                </div>
                                <div class="text-right mt-3 flex-1">
                                    <button class="btn btn-warning p-3 mr-2 mb-2 next_payment">Перейти до оплати</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
{{--@include('js_function')--}}

@push('footer_scripts')
    <script>
        $(document).on('click', '.changeTariff', function (e) {
            e.preventDefault();

            let childId = $(this).data('child-id');
            let study = $(this).data('study');

            $(`.changeTariff[data-child-id=${childId}]`).removeClass('btn_change_tariff_active').addClass('btn_change_tariff').text('Обрати план');
            $(this).addClass('btn_change_tariff_active').text('Обраний пакет');

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
                let study = $('#study_'+id).val();
                if (study === '{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LEARNER]['value']}}') {
                    sum[id] = Number(sum[id]) + tariffLearner - Number({{$price['sale']}})-Number({{$price['referal']}}) - this.getPercentDiscount(tariffLearner, childDiscount);
                }

                if (study === '{{$tariffs[\App\Helpers\PaymentHelpers::TARIFF_LISTENER]['value']}}') {
                    sum[id] = Number(sum[id]) + tariffListener - Number({{$price['sale']}})-Number({{$price['referal']}}) - this.getPercentDiscount(tariffListener, childDiscount);
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
                @foreach($item as $value)
                    document.getElementById('children_{{$value['id']}}').style = 'display: none;';
                @endforeach
                document.getElementById(`children_${id}`).style = '';
            }
        }
    </script>
@endpush
