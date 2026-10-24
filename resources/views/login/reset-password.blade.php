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
                    {{--<img alt="Rubick Tailwind HTML Admin Template" class="w-4/5" src="https://jammschool.com.ua/wp-content/uploads/2020/12/logo_black.svg">--}}
                    {{--<img alt="Rubick Tailwind HTML Admin Template" class="w-44" src="https://jammschool.com.ua/wp-content/uploads/2020/12/logo_black.svg">--}}
                    <img alt="JAMM School" class="w-48" src="{{ asset('dist/img/logo_black.svg') }}">
                </a>
                <div class="my-auto">
                    <img alt="Rubick Tailwind HTML Admin Template" class="-intro-x w-1/2 -mt-16" src="{{ asset('dist/images/illustration.svg') }}">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">Вітаємо в фінансовому кабінеті</div>
                    {{--<div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-gray-500">Manage all your e-commerce accounts in one place</div>--}}
                </div>
            </div>
            <!-- END: Login Info -->
            <!-- BEGIN: Login Form -->
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-dark-1 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">Скинути пароль</h2>
                    <div class="flex mt-5 justify-center md:hidden" style="height: 20px;">
                        <img src="{{ asset('dist/img/logo_black.svg') }}" style="max-width: 100%; height: auto;">
                    </div>
                    <div class="intro-x mt-8">

                        @if (session('status'))
                            <div class="alert show alert-success mb-4">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert show alert-danger mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="login-form" action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <div class="mt-2"></div>
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input id="email" name="email" type="text" class="intro-x login__input form-control py-3 px-4 border-gray-300 block" value="{{ request()->get('email') }}" placeholder="Емейл">

                            <div class="mt-2"></div>
                            <input id="password" type="password" name="password" class="intro-x login__input form-control py-3 px-4 border-gray-300 block" placeholder="Новий пароль">

                            <div class="mt-2"></div>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="intro-x login__input form-control py-3 px-4 border-gray-300 block" placeholder="Підтвердження нового пароля">

                            <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                                <button id="btn-login" class="btn btn-warning mr-1 mb-2">Зберегти</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END: Login Form -->
        </div>
    </div>
@endsection
