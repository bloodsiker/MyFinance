@extends('../layout/' . $layout)

@section('subhead')
    <title>Процес реєстрації</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-12 xxl:col-span-12">
            <!-- BEGIN: Personal Information -->
            <div class="intro-y box mt-5">
                <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">Exchange</h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-12 gap-x-5">
                        <div class="col-span-12 xl:col-span-4">
                            <div class="mt-3">
                                <label for="from_ticker" class="form-label">Віддаємо валюту</label>
                                <select id="from_ticker" class="form-select" disabled>
                                    <option value="USDT">USDT</option>
                                    <option value="CNY" selected>CNY</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="from_amount" class="form-label">Віддаємо <span style="color: green">Курс 1 CNY - {{ round($cnyUsd['data']['amount'], 3) }} $</span></label>
                                <input id="from_amount" type="number" class="form-control" placeholder="1" value="100">
                            </div>
                        </div>
                        <div class="col-span-12 xl:col-span-4">
                            <div class="mt-3">
                                <label for="to_ticker" class="form-label">Отримуємо валюту</label>
                                <select id="to_ticker" class="form-select" disabled>
                                    <option value="USDT" selected>USDT</option>
                                    <option value="CNY">CNY</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="to_amount" class="form-label">Отримуємо <span style="color: green">Курс 1 $ - {{ round($usdCny['data']['amount'], 3) }} CNY</span></label>
                                <input id="to_amount" type="number" class="form-control" placeholder="1" value="">
                            </div>
                            <input id="rate_to" type="hidden" class="form-control" placeholder="1" value="">
                        </div>

                        <div class="col-span-12 xl:col-span-4">
                            <div class="mt-3">
                                <label for="to_ticker_uah" class="form-label">Отримуємо UAH</label>
                                <select id="to_ticker_uah" class="form-select" disabled>
                                    <option value="UAH" selected>UAH</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="rate_uah" class="form-label">Курс $/UAH</label>
                                <input id="rate_uah" type="number" class="form-control" placeholder="0" value="{{ round($uah['data']['amount'], 2) }}">
                            </div>
                            <div class="mt-3">
                                <label for="amount_uah" class="form-label">Сума UAH</label>
                                <input id="amount_uah" type="number" class="form-control" placeholder="0" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Personal Information -->
        </div>
    </div>

@endsection

@push('footer_scripts')
    <script>

        $(document).ready(function() {
            let fromTicker = $("#from_ticker").val();
            let toTicker = $("#to_ticker").val();

            console.log(fromTicker, toTicker);

            calculateExchange(fromTicker, toTicker);
        });

        $('#from_amount').on('input', function () {
            let rateTo = $('#rate_to').val();
            let fromAmount = $('#from_amount').val();
            // let toAmount = $('#to_amount').val();
            let rateUah = $('#rate_uah').val();

            let newToAmount = parseFloat(fromAmount * rateTo).toFixed(2);
            $('#to_amount').val(newToAmount);
            $('#amount_uah').val(parseFloat(newToAmount * rateUah).toFixed(2));
        })

        $('#to_amount').on('input', function () {
            let rateTo = $('#rate_to').val();
            // let fromAmount = $('#from_amount').val();
            let toAmount = $('#to_amount').val();
            let rateUah = $('#rate_uah').val();

            console.log(toAmount, rateTo);

            let newFromAmount = parseFloat(toAmount / rateTo).toFixed(2);
            $('#from_amount').val(newFromAmount);
            $('#amount_uah').val(parseFloat(toAmount * rateUah).toFixed(2));
        })

        $('#rate_uah').on('input', function () {
            let rateTo = $('#rate_to').val();
            let fromAmount = $('#from_amount').val();
            // let toAmount = $('#to_amount').val();
            let rateUah = $('#rate_uah').val();

            let newToAmount = parseFloat(fromAmount * rateTo).toFixed(2);
            $('#to_amount').val(newToAmount);
            $('#amount_uah').val(parseFloat(newToAmount * rateUah).toFixed(2));
        })

        const calculateExchange = (fromTicker, toTicker) => {
            $.ajax({
                method: "GET",
                url: '{{ route('exchange.rate') }}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: { fromTicker: fromTicker, toTicker: toTicker}
            }).done(function (response) {
                let rateTo = parseFloat(response.data.amount).toFixed(3);
                let fromAmount = $('#from_amount').val();
                let newToAmount = parseFloat(fromAmount * rateTo).toFixed(2);

                $('#to_amount').val(newToAmount);
                $('#rate_to').val(rateTo);

                let rateUah = $('#rate_uah').val();
                $('#amount_uah').val(parseFloat(newToAmount * rateUah).toFixed(2));
            });
        }
    </script>
@endpush
