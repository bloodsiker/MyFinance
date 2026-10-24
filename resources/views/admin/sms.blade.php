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
                    <div align="center">
                        @if (Auth::user()->role_id > 1)
                            <div class="intro-y box p-5">
                                <div class="sms_balance">
                                    На рахунку: {{ $balance['amount'] }} {{ $balance['currency'] }}
                                </div>

                                @if ($errors->any())
                                    <div class="alert show alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (session()->has('success'))
                                    <div class="alert show alert-success">
                                        {{ session()->get('success') }}
                                    </div>
                                @endif

                                @if (session()->has('error'))
                                    <div class="alert show alert-danger">
                                        {{ session()->get('error') }}
                                    </div>
                                @endif

                                <form action="{{route('admin.send_sms')}}" enctype="multipart/form-data" method="post">
                                    @csrf
                                    <div class="post__content tab-content">
                                        <div id="content" class="tab-pane p-5 active" role="tabpanel" aria-labelledby="content-tab">
                                            <div class="border border-gray-200 dark:border-dark-5 rounded-md p-5 mt-5">
                                                <div class="mt-5">
                                                    <div>
                                                        <label for="post-name-7" class="form-label">Номер телефону</label>
                                                        <input id="post-name-7" name="phone" type="text"
                                                               class="form-control" value="{{ old('phone') }}" placeholder="Номер телефону">
                                                    </div>
                                                    <br>
                                                    <div>
                                                        <label for="post-name-7" class="form-label">Текст смс</label>
                                                        <input id="post-name-7" name="text" type="text"
                                                               class="form-control" value="{{ old('text') }}" placeholder="Текст">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-warning w-24 inline-block mr-1 mb-2">Відправити</button>
                                </form>
                            </div>
                            <div style="height: 30px"></div>
                        @endif
                        <div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
