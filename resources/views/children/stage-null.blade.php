@extends('../layout/' . $layout)

@section('subhead')
    <title>Процес реєстрації</title>
@endsection

@section('subcontent')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center h-10">
                        <h2 style="width: 100%" class="text-center text-lg font-medium truncate mr-5">Процес реєстрації в JAMM School дуже простий</h2>
                    </div>

                    @include('progress', ['colors'=>['bg-theme-12','bg-gray-200','bg-gray-200','bg-gray-200','bg-gray-200']])

                    <div style="height: 15px"></div>
                    <div class="intro-y box p-5">
                        <form method="post" action="{{route('first_move')}}">
                            @csrf

                            <div class="alert alert-warning show flex items-center mb-2" role="alert" style="margin: 20px">
                                <h3 style="width: 100%" class="text-center font-medium truncate mr-5">Ви на самому початку реєстрації в JAMM SCHOOL - школи, в якій розвиток унікальних здібностей та талантів дитини займає центральне місце. <br> Наша мета, за допомогою якісної освіти в JAMM24, допомогти Вашій дитині стати зіркою в школі, університеті, потім в компанії де він/вона буде працювати і цей процес починається прямо зараз! <br>
                                    Реєстрація складається з 4 етапів. Кожний наступний етап є продовженням попереднього: Анкета, Оплата, Навчання, Документи. <br>
                                    Перший Ви вже пройшли!
                                    Якщо всі поля на цій сторінці заповнені правильно, натискайте “Далі”</h3>
                            </div>

                            <div style="height: 10px"></div>
                            <div>
                                <label for="last_name" class="form-label">Прізвище одного з батьків</label>
                                <input required onchange="checkName('last_name')" name="last_name" id="last_name" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}" type="text" class="form-control" value="{{$item->last_name}}" placeholder="Прізвище">
                            </div>

                            <div style="height: 10px"></div>
                            <div>
                                <label for="user_name" class="form-label">Ім'я одного з батьків</label>
                                <input required onchange="checkName('user_name')" name="user_name" id="user_name" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}" type="text" class="form-control" value="{{$item->user_name}}" placeholder="Ім'я">
                            </div>

                            <div style="height: 10px"></div>
                            <div>
                                <label for="middle_name" class="form-label">По батькові одного з батьків</label>
                                <input required onchange="checkName('middle_name')" name="middle_name" id="middle_name" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}" type="text" class="form-control" value="{{$item->middle_name}}" placeholder="По батькові">
                            </div>

                            <div style="height: 10px"></div>
                            <div>
                                <label for="phone" class="form-label">Телефон одного з батьків</label>
                                <input id="phone" required class="intl-phone form-control" name="virtual_phone" value="{{$item->phone}}" type="tel">
                                <input type="hidden" class="user_phone" name="phone">
                                <span class="error-msg"></span>
                            </div>

                            <div style="height: 10px"></div>
                            <div>
                                <label for="telegram" class="form-label">Ваш нікнейм у телеграмі</label>
                                <input name="telegram" id="telegram" title="Ваш нікнейм у телеграмі" type="text" class="form-control" value="{{$item->telegram}}">
                            </div>

                            <div style="height: 10px"></div>
                            <div>
                                <label for="whatsapp" class="form-label">Ваш номер у whatsapp</label>
                                <input name="whatsapp" id="whatsapp" title="Ваш номер у whatsapp" type="text" class="form-control" value="{{$item->whatsapp}}">
                            </div>

                            <div style="height: 10px"></div>
                            <div>
                                <label for="email" class="form-label">email одного з батьків</label>
                                <input required onchange="checkEmail('email')" name="email" id="email" type="email" class="form-control" value="{{$item->email}}" placeholder="email@gmail.com">
                            </div>

                            <div style="height: 10px"></div>
                            <div>
                                <h2 style="width: 100%">Скількох дітей Ви реєструєте в школу?
                                    <select style="background-color: white" class="form-control w-full" onchange="childNames()" name="count" id="count">
                                        <option selected value="1">1</option>
                                        @for($i = 2; $i <= 10; $i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </h2>
                            </div>



                            <div id="child_name_div">
                                <label for="child_name_1" class="form-label">Ім'я учня</label>
                                <input onchange="checkName('child_name_1')" required id="child_name_1" title="Не дозволено використовувати числа і символи крім апострофа і дефіса." pattern="^[A-Za-zА-Яа-яЁёґєіїҐЄІЇ ’'`-]{2,}" name="child_name[]" type="text" class="form-control" placeholder="Ім'я учня">
                            </div>
                            <div class="text-right mt-5">
                                <button class="btn btn-warning w-24 mr-1 mb-2">далі</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@include('js_function')

@push('footer_scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>

    <script>
        const input = document.querySelector(".intl-phone");
        const errorMsg = document.querySelector(".error-msg");

        const errorMap = ["Невірний номер", "Невірний код країни", "Занадто короткий", "Занадто довгий", "Невірний номер"];

        const iti = window.intlTelInput(input, {
            initialCountry: "UA",
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
        });

        const reset = () => {
            input.classList.remove("phone-error");
            errorMsg.innerHTML = "";
            errorMsg.classList.add("hide");
        };

        const getPhoneNumberWithCountryCode = () => {
            return iti.getNumber();
        };

        const validateNumber = () => {
            reset();

            const userPhoneInput = document.querySelector('.user_phone');
            userPhoneInput.value = getPhoneNumberWithCountryCode();
            // console.log(iti.getSelectedCountryData());

            if (input.value.trim()) {
                if (!iti.isValidNumber()) {
                    input.classList.add("phone-error");
                    const errorCode = iti.getValidationError();

                    if (errorMap[errorCode] !== undefined) {
                        errorMsg.innerHTML = errorMap[errorCode];
                        errorMsg.classList.remove("hide");
                    }
                }
            }
        }

        input.addEventListener('keyup', validateNumber);
        input.addEventListener('change', validateNumber);
        window.addEventListener("load", function() {
            setTimeout(function (){
                validateNumber();
            }, 1000)
        });
    </script>
@endpush
