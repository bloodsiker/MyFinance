@extends('../layout/' . $layout)

@section('head')
    <title>Увійти</title>
@endsection

@section('content')
    <div class="container sm:px-10">
        <div class="block xl:grid grid-cols-2 gap-4">
            <!-- BEGIN: Login Info -->
            <div class="hidden xl:flex flex-col min-h-screen">
                <a href="" class="-intro-x flex items-center pt-5">
                    <img alt="JAMM School" class="w-48" src="{{ asset('dist/img/logo.png') }}">
                </a>
                <div class="my-auto">
                    <img alt="Rubick Tailwind HTML Admin Template" class="-intro-x w-1/2 -mt-16" src="{{ asset('dist/images/illustration.svg') }}">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">Вітаємо в фінансовому кабінеті</div>
                </div>
            </div>
            <!-- END: Login Info -->
            <!-- BEGIN: Login Form -->
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-dark-1 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">Увійти</h2>
                    <div class="flex mt-5 justify-center md:hidden" style="height: 20px;">
                        <img src="{{ asset('dist/img/logo_black.svg') }}" style="max-width: 100%; height: auto;">
                    </div>
                    <div class="intro-x mt-8">

                        @if (session('status'))
                            <div class="alert show alert-success mb-4">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form id="login-form">
                            <div id="error-user" class="login__input-error w-5/6 text-theme-6 mt-2"></div>
                            <input id="email" type="text" class="intro-x login__input form-control py-3 px-4 border-gray-300 block" placeholder="Емейл"{{-- value="midone@left4code.com"--}}>
                            <div id="error-email" class="login__input-error w-5/6 text-theme-6 mt-2"></div>
                            <input id="password" type="password" class="intro-x login__input form-control py-3 px-4 border-gray-300 block mt-4" placeholder="Пароль" {{--value="password"--}}>
                            <div id="error-password" class="login__input-error w-5/6 text-theme-6 mt-2"></div>
                        </form>
                    </div>
                    <div class="intro-x flex text-gray-700 dark:text-gray-600 text-xs sm:text-sm mt-4">
                        <div class="flex items-center mr-auto">
                            <input id="remember-me" type="checkbox" class="form-check-input border mr-2">
                            <label class="cursor-pointer select-none" for="remember-me">Запам'ятати мене</label>
                        </div>
{{--                        <a href="{{ route('forgot-password') }}">Забули пароль?</a>--}}
                    </div>
                    <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                        <button id="btn-login" class="btn btn-warning w-24 mr-1 mb-2">Увійти</button>
                    </div>
                    {{--<div class="intro-x mt-10 xl:mt-24 text-gray-700 dark:text-gray-600 text-center xl:text-left">
                        By signin up, you agree to our <br> <a class="text-theme-1 dark:text-theme-10" href="">Terms and Conditions</a> & <a class="text-theme-1 dark:text-theme-10" href="">Privacy Policy</a>
                    </div>--}}
                </div>
            </div>
            <!-- END: Login Form -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        cash(function () {
            async function login() {
                // Reset state
                cash('#login-form').find('.login__input').removeClass('border-theme-6')
                cash('#login-form').find('.login__input-error').html('')

                // Post form
                let email = cash('#email').val()
                let password = cash('#password').val()
                let rememberMe = cash('#remember-me').val()

                // Loading state
                cash('#btn-login').html('<i data-loading-icon="oval" data-color="white" class="w-5 h-5 mx-auto"></i>').svgLoader()
                await helper.delay(1500)

                axios.post(`login`, {
                    email: email,
                    password: password,
                    remember_me: rememberMe
                }).then(res => {
                    location.href = res.data.url;
                }).catch(err => {
                    cash('#btn-login').html('Login')
                    if (err.response.data.message == 'Wrong email or password.') {
                        cash(`#password`).addClass('border-theme-6')
                        cash(`#error-password`).html(err.response.data.message)
                    } else if (err.response.data.message == 'Blocked account') {
                        cash(`#error-user`).html('Обліковий запис заблокований')
                    } else {
                        for (const [key, val] of Object.entries(err.response.data.errors)) {
                            cash(`#${key}`).addClass('border-theme-6')
                            cash(`#error-${key}`).html(val)
                        }
                    }
                })
            }

            cash('#login-form').on('keyup', function(e) {
                if (e.keyCode === 13) {
                    login()
                }
            })

            cash('#btn-login').on('click', function() {
                login()
            })
        })
    </script>
@endsection
