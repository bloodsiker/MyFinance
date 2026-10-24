@extends('../layout/' . $layout)

@section('subhead')
    <title>Процес реєстрації</title>
@endsection

@section('subcontent')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">

                    <div class="intro-y flex items-center h-10"></div>

                    @include('progress', ['colors'=>['bg-theme-9','bg-theme-9','bg-theme-9','bg-theme-9','bg-theme-9']])

                    <div style="height: 15px"></div>
                    <div align="center">
                        <div style="width: 83%" class="intro-y box p-5">
                            <form {{--method="post" action="{{route('fourth_step')}}"--}}>
                                @csrf
                                <div class="intro-y flex items-center h-10">
                                    <h2 style="width: 100%" class="text-center text-lg font-medium truncate mr-5">Вітаємо. Ви завершили завантаження всіх документів <br> <b>Ваша дитина 100% JAMMER</b></h2>


                                </div>
                                <div style="height: 15px"></div>
                                <div class="leading-relaxed">
                                    <div>
                                        <img src="{{ asset('dist/img/QR.jpg') }}" alt="qr code" style="height:500px; weidth:400px">
                                      <br>
                                      <br>
                                    <a href="https://t.me/+mLXozUi8oc40Mzhi" target = "_blank">Телеграм канал школи - https://t.me/+mLXozUi8oc40Mzhi</a>
                                </div>

                                </div>


                                <div class="alert alert-warning show flex items-center mb-2" role="alert" style="margin: 20px">
                                    <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i><h2 style="width: 100%" class="text-center font-medium mr-5">
                                        На пошту Вашої дитини вже прийшло запрошення приступити до навчання на платформі JAMM24<br>
                                        ПС: перевірте спам ящик 🙂</h2>
                                </div>



                                <div style="height: 15px"></div>
                                <div>
                                    <div style="height: 5%"></div>
                                    <div>
                                        <button  class="btn btn-outline-success w-40 inline-block mr-1 mb-2">Завершити</button>
                                    </div>
                                    <div style="height: 5%"></div>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
