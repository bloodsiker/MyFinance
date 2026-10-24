@extends('../layout/' . $layout)

@section('subhead')
    <title>Процес реєстрації</title>
@endsection

@section('subcontent')
    {{--<div></div>
    Процесс регистрации в Джемм Скул очень простой--}}

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">
                    @if(Auth::user()->stageRegister === 8)
                        @include('progress', ['colors'=>['bg-theme-9','bg-theme-12','bg-theme-9','bg-theme-9','bg-theme-9']])
                    @else
                        @include('progress', ['colors'=>['bg-theme-9','bg-theme-12','bg-gray-200','bg-gray-200','bg-gray-200']])
                    @endif

                    <div style="height: 15px"></div>
                    <div class="intro-y flex items-center h-10">
                        <h2 style="width: 100%" class="text-center text-lg font-medium truncate mr-5">Оберіть зручний спосіб оплати</h2>
                    </div>
                    <div align="center">
                        <div class="intro-y box p-5">
                            @csrf
                            <input name="payment_method" id="pay" style="display: none">
                            <div style="height: 15px"></div>
                            {{-- <a class="form-label" target="_blank">Договір публічної оферти</a><br> --}}

                          {{-- <br>
                            <a href="https://drive.google.com/file/d/1Uvqydqw2SDnpX8eHqv-T80zmpUyTbGWw/view?usp=sharing" class="btn btn-warning w-40 mr-1 mb-2" target="_blank">Ознайомитись</a>
                            <br> --}}
                            <input style="margin:10px; width: 30px; display:inline;
                            height: 30px;" onclick="f()" type="checkbox" required class="show-code col-span-4  mr-0 ml-3">
                            <p style="margin-bottom:5px; display:inline; font-size:18px;">Я погоджуюсь з умовами  <b> <a style="color:blue; text-decoration:underline " href="https://docs.google.com/document/d/1VaZFrpsKIu4F24grze1JpEuV4yUHM2_X/edit"  target="_blank">договору публічної оферти</a></b>  <br><em>( Для проведення оплати потрібно поставити відмітку )</em>
                               </p>
                               <br>
                            {{-- <label> <b>ВІДМІТЬТЕ ТУТ ( поставивши галочку )</b> </label> --}}
                            {{-- <input style="margin-top:5px" onclick="f()" type="radio" required class="show-code col-span-2 form-check-switch mr-0 ml-3"> --}}

                            <div style="height: 50"></div>
                            <div align="center">
                                <b><h2 id="actual-price" style="width: 100%; font-size:20px; margin-bottom:50px;">сума до оплати: {{$item['amount']}} грн</h2></b>

                            </div>
                            <?php echo $item['buttons'] ?>
                            <div style="height: 15px"></div>
                            <p style="margin-bottom:5px; display:inline; font-size:18px;">
                                При оплаті Послуг з використанням електронних коштів Користувача буде<br>переадресовано на Платіжну сторінку банку, яка захищена згідно з правилами<br>міжнародних платіжних систем VISA, Mastercard
                            </p>
                            {{--<h3 style="width: 100%" class="text-center font-medium truncate mr-5">Додайте карту і ваші платежі будуть здійсьнюватися автоматично</h3>
                            <div style="height: 15px"></div>
                            <div align="center">
                                <div>
                                    <button style="width: 30%;" class="btn btn-outline-primary w-24 inline-block mr-1 mb-2">Добавить карту</button>
                                </div>
                                <div style="height: 5%"></div>
                            </div>--}}

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
<script>
    function f() {
        for (let button of document.getElementsByName('btn_text')) {
            button.removeAttribute('disabled');
        }
    }
</script>
