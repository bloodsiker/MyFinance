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

                    <div align="center">
                        <div style="width: 83%" class="intro-y box p-5">
                            <form method="post" action="{{route('pay')}}">
                                @csrf
                                <input name="status" id="pay" style="display: none">
                                <div class="intro-y flex items-center h-10">
                                    <h2 style="width: 100%" class="text-center text-lg font-medium truncate mr-5">Какая-то старница для оплаты методом {{$item['method']}}</h2>
                                </div>
                                <div class="intro-y flex items-center h-10">
                                    <h2 style="width: 100%" class="text-center text-lg font-medium truncate mr-5">Потом будет редирект на платежку</h2>
                                </div>
                                <div align="center">
                                    <div style="height: 15px"></div>
                                    <div>
                                        <button style="width: 30%;" onclick="document.getElementById('pay').value='yes'" class="btn btn-outline-success w-24 inline-block mr-1 mb-2">имитация отплаты</button>
                                        <button style="width: 30%;" onclick="document.getElementById('pay').value='no'" class="btn btn-outline-danger w-24 inline-block mr-1 mb-2">имитация ожидания</button>
                                    </div>

                                    <div style="height: 15px"></div>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
