@extends('../layout/' . $layout)
@section('subhead')
    <title>Процес реєстрації</title>
@endsection

@section('subcontent')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">

                    @include('progress', ['colors'=>['bg-theme-9','bg-theme-9','bg-theme-9','bg-theme-9','bg-theme-12']])

                    <div class="boxed-tabs nav nav-tabs justify-center border border-gray-400 border-dashed text-gray-600 dark:border-gray-700 dark:bg-dark-3 dark:text-gray-500 rounded-md p-1 mt-5 mx-auto" role="tablist">
                        @foreach($item as $key => $children)
                            @if($key===0)
                                <a onclick="tabForm({{$children['id']}})" data-toggle="tab" data-target="#inactive-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2 active" id="inactive-users-tab" role="tab" aria-selected="true">{{$children['name']}}</a>
                            @else
                                <a onclick="tabForm({{$children['id']}})" data-toggle="tab" data-target="#active-users" href="javascript:;" class="btn flex-1 border-0 shadow-none py-1 px-2" id="active-users-tab" role="tab" aria-controls="active-users" aria-selected="false">{{$children['name']}}</a>
                            @endif
                        @endforeach

                    </div>

                    @if(!count($item))
                        <div class="alert alert-danger show flex items-center mb-2" role="alert">
                            <i data-feather="alert-octagon" class="w-6 h-6 mr-2"></i> У вас не додано ні одного учня, для подальшої реестрації зверніться до менеджерів
                        </div>
                    @endif

                    @if(count($item))
                        <form method="post" action="{{route('fifth_step')}}" enctype="multipart/form-data">
                            <div class="intro-y box p-5">
                                @foreach($item as $key => $children)
                                    <div id="children_{{$children['id']}}">
                                        @if($key!==0)
                                            <script>
                                                document.getElementById('children_{{$children['id']}}').style = 'display: none;';
                                            </script>
                                        @endif

                                        @csrf
                                            <div style="border-radius: 5px;box-shadow: 0 0 10px rgba(92,92,92,0.5);" id="faq-accordion-20-{{$children['id']}}" class="accordion p-4">
                                                <br>
                                                <div class="alert alert-secondary show mr-3 training_format_item" role="alert">
                                                    <h1 style="width: 100%; font-size: 20px" class="text-center font-medium truncate mr-10">Останній етап реєстрації! <br>
                                                        Будь ласка, завантажте необхідні для зарахування дитини в школу документи,<br> доповніть дані місця перебування та оберіть канал спілкування.<br>
                                                        Побачимось у батьківському кабінеті!</h1>
                                                </div>
                                                <br>
                                                <div class="alert alert-warning show flex items-center mb-2" role="alert">
                                                    <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> Всі документи необхідні для ідентифікації особи учня та батьків на підставі Наказу МОН України № 367 від 16.04.2018 року     <a style="margin-left: 5%"  target="_blank" type="button" href="https://mon.gov.ua/storage/app/media/gromadske-obgovorennya/2018/05/05/Poryadok zarahuvannya do pershogo klasu.pdf" class="btn btn-dark w-24 mr-1 mb-2">Наказ</a>


                                                </div>
                                                <br>
                                                <br>
                                                <label>Фото свідоцтва про народження дитини</label>
                                                <label for="certificate-{{$children['id']}}">
                                                    <div class="dropzone">
                                                        <div class="fallback">
                                                            <input required onchange="test(`certificate-{{$children['id']}}`)"  name="children[{{$children['id']}}][certificate]" id="certificate-{{$children['id']}}" type="file" data-dz-thumbnail="" style="border-radius: 20px; width:auto;"  />
                                                        </div>
                                                        <div class="dz-message" data-dz-message>
                                                            <div class="text-lg font-medium">Натисніть, щоб завантажити свідоцтво. <br> <em> ( Обовʼязкове для завантаження ) </em> </div>
                                                            <div class="flex w-100 justify-start" style="max-height: 150px;height: 150px">
                                                                <div class="flex">
                                                                    <img data-dz-thumbnail="" id="certificate-{{$children['id']}}-file" style="border-radius: 20px; width:auto;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>

                                                <div style="height: 15px"></div>
                                                <div>
                                                    <a href="https://docs.google.com/document/d/1aem3znKlsfGHr412NTJvxfImhb5iUYdQjWgOAb59SWI/edit?usp=sharing" class="btn btn-rounded btn-primary-soft" target="_blank">Завантажити порожній бланк заяви для заповнення </a>
                                                </div>
                                                <div style="height: 15px"></div>
                                                <label for="statement-{{$children['id']}}">Завантажте заповнену і підписану заяву</label>
                                                <label for="statement-{{$children['id']}}">

                                                    <div class="dropzone">
                                                        <div class="fallback">
                                                            <input required onchange="test(`statement-{{$children['id']}}`)"  name="children[{{$children['id']}}][statement]" id="statement-{{$children['id']}}" type="file" data-dz-thumbnail="" style="border-radius: 20px; width:auto;"  />
                                                        </div>
                                                        <div class="dz-message" data-dz-message>
                                                            <div class="text-lg font-medium">Натисніть, щоб завантажити заяву.  <br> <em>( Обовʼязкове для завантаження )</em>

                                                            </div>
                                                            <div class="flex w-100 justify-start" style="max-height: 150px;height: 150px">
                                                                <div class="flex">
                                                                    <img id="statement-{{$children['id']}}-file" style="border-radius: 20px; width:auto;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </label>

                                            </div>

                                    </div>

                                @endforeach
                                    <div style="height: 15px"></div>

                                    <label for="passport">Фото трьох сторінок паспорта, або ID картки одого з батьків</label>
                                    <label for="passport">
                                        <div class="dropzone">
                                            <div class="fallback">
                                                <input required onchange="testMul(`passport`)"   name="passport[]" max="3" id="passport" multiple  type="file" data-dz-thumbnail="" style="border-radius: 20px; width:auto;" />
                                            </div>
                                            <div class="dz-message" data-dz-message>
                                                <div class="text-lg font-medium">Натисніть, щоб завантажити фото паспорта.  <br><em>( Обовʼязкове для завантаження )</em>
                                                    <br> Для завантаження декількох сторінок паспорта виділіть одразу всі сторінки які бажаєте доєднати
                                                </div>
                                                <div class="flex w-100 justify-start" style="max-height: 150px;height: 150px">
                                                    <div class="flex">
                                                        <img id="passport-file-0" data-dz-thumbnail="" style="border-radius: 20px; width:auto;">
                                                        <img id="passport-file-1" data-dz-thumbnail="" style="border-radius: 20px; width:auto;">
                                                        <img id="passport-file-2" data-dz-thumbnail="" style="border-radius: 20px; width:auto;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <div style="height: 15px"></div>


                                    <h2 style="width: 100%" class="font-medium truncate mr-5">Домашня адреса</h2>
                                    <br>
                                    <div class="alert alert-warning-soft show flex items-center mb-2" role="alert">
                                        <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> Всі поля обовʼязкові для заповненя
                                    </div>
                                    <div>
                                        <label for="city"  class="form-label">Місто</label>
                                        <input required name="city" type="text" class="form-control" placeholder="Місто">
                                    </div>
                                    <div>
                                        <label for="street" class="form-label">Вулиця</label>
                                        <input required name="street" type="text" class="form-control" placeholder="Вулиця">
                                    </div>
                                    <div>
                                        <label for="house" class="form-label">Будинок/Квартира</label>
                                        <input required name="house" type="text" class="form-control" placeholder="Будинок/Квартира">
                                    </div>
                                    <div>
                                        <label for="post" class="form-label">Відділення нової пошти або укрпошти</label>
                                        <input name="post" type="text" class="form-control" placeholder="Відділення нової пошти">
                                    </div>

                                    {{-- <div class="grid grid-cols-12 gap-2">
                                        <div align="center" class="col-span-1"><input required type="radio" class="show-code form-check-switch mr-0 ml-3" id="communication" name="communication" value="email"></div>
                                        <output type="text" class="col-span-11">email</output>
                                    </div>
                                    <div style="height: 15px;"></div>
                                    <div class="grid grid-cols-12 gap-2">
                                        <div align="center" class="col-span-1"><input required type="radio" class="show-code form-check-switch mr-0 ml-3" id="communication" name="communication" value="sms"></div>
                                        <output type="text" class="col-span-11">смс</output>
                                    </div> --}}
    <br>
    <br>
                                    <h2 style="width: 100%; display: none" class="text-center font-medium truncate mr-5"><b>Важливо! Пам'ятайте, що Вам необхідно <br> відправити оригінал особової справи учня нам в офіс. <br> <b>Київський поштомат Нової пошти №2597</b>.<br> Отримувач: Кравченко Оксана,
                                        +380635682022 - протягом 11 днів.<br></b>
                                        Підтвердіть відправку документів, надіславши нам <br> номер накладної.</h2>
                                    <div style="height: 15px display: none"></div>
                                    <div align="center" >
                                        <input required checked type="checkbox" style="display: none">
                                        <output style="display: none" type="text"><b>ОЗНАЙОМИВСЯ(ЛАСЬ)</b></output>
                                        <br>
                                        <br>

                                        <div class="alert alert-danger show flex items-center mb-2" role="alert">
                                            <i data-feather="alert-octagon" class="w-6 h-6 mr-2"></i> Якщо кнопка далі не переводить Вас на наступну сторінку - впевніться, що всі поля заповнено
                                        </div>
                                        <br>
                                    </div>
                                    <div style="height: 15px"></div>
                                    <div>
                                    </div>
                                    <div align="center">
                                        <div class="text-right mt-5">
                                            @if(!count($item))
                                                <button class="btn btn-warning w-24 mr-1 mb-2 disabled" disabled>Далі</button>
                                            @else
                                                <button class="btn btn-warning w-24 mr-1 mb-2">Далі</button>
                                            @endif
                                        </div>
                                    </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
@include('js_function')
<script>
    function tabForm(id) {
        @foreach($item as $value)
        document.getElementById('children_{{$value['id']}}').style = 'display: none;';
        @endforeach
        document.getElementById(`children_${id}`).style = '';
    }
    function test(id) {
        if(document.getElementById(id).files.length > 3) {
            alert('Не больше 3 изображений. Попробуйте ещё раз.');
            return false;
        }
        var file    = document.getElementById(id).files[0];
        var preview = document.getElementById(id+'-file');
        var reader  = new FileReader();

        if (document.getElementById(id).files[0].size > 5252484) {
            preview.src = '';
            preview.alt = '';
            document.getElementById(id).files[0] = null;
            alert("Розмір файлу не повинен перевищувати 5мб!");
        } else {
            reader.onloadend = function () {
                preview.src = reader.result;
                preview.alt = document.getElementById(id).files[0].name;
            };
            if (file) {
                reader.readAsDataURL(file);
            }
        }
    }

    function testMul(id) {
        if(document.getElementById(id).files.length > 3) {
            alert('не більше 3 зображень. Спробуйте ще раз.');
            return false;
        }
        var file    = document.getElementById(id).files;
        var preview = [];
        preview[0] = document.getElementById(id+'-file-0');
        preview[1] = document.getElementById(id+'-file-1');
        preview[2] = document.getElementById(id+'-file-2');
        preview[0].src = '';
        preview[1].src = '';
        preview[2].src = '';
        var reader1  = new FileReader();
        var reader2  = new FileReader();
        var reader3  = new FileReader();

        reader1.onloadend = function () {

            preview[0].src = reader1.result;
            preview[0].alt = document.getElementById(id).files[0].name;

        };
        reader2.onloadend = function () {

            preview[1].src = reader2.result;
            preview[1].alt = document.getElementById(id).files[1].name;

        };
        reader3.onloadend = function () {

            preview[2].src = reader3.result;
            preview[2].alt = document.getElementById(id).files[3].name;

        };

        if (file) {
            for (let fl in file){
                console.log(fl)
                if (fl === '0') {
                    console.log(1);
                    reader1.readAsDataURL(file[fl]);
                }
                if (fl === '1') {
                    console.log(1);
                    reader2.readAsDataURL(file[fl]);
                }
                if (fl === '2') {
                    console.log(1);
                    reader3.readAsDataURL(file[fl]);
                }
            }
        }
    }


    function getBase64Image(img) {
        console.log(img)
        // Create an empty canvas element
        var canvas = document.createElement("canvas");
        canvas.width = img.width;
        canvas.height = img.height;
        console.log(canvas)
        // Copy the image contents to the canvas
        var ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0);

        // Get the data-URL formatted image
        // Firefox supports PNG and JPEG. You could check img.src to
        // guess the original format, but be aware the using "image/jpg"
        // will re-encode the image.
        var dataURL = canvas.toDataURL("image/png");

        return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
    }
</script>
