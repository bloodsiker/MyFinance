@extends('../layout/' . $layout)
@section('subhead')
    <title>Процес реєстрації</title>
@endsection

@section('subcontent')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">

                    <form method="post" action="{{route('children.update.step_3', ['id' => $children->id])}}" enctype="multipart/form-data">
                        <div class="intro-y box p-5">
                            <div id="children_{{$children['id']}}">

                                @csrf
                                <div style="border-radius: 5px;box-shadow: 0 0 10px rgba(92,92,92,0.5);" id="faq-accordion-20-{{$children['id']}}" class="accordion p-4">
                                    <br>
                                    <div class="alert alert-secondary show mr-3 training_format_item" role="alert">
                                        <h1 style="width: 100%; font-size: 20px" class="text-center font-medium truncate mr-10">Останній етап реєстрації! <br>
                                            Будь ласка, завантажте необхідні для зарахування дитини в школу документи.</h1>
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
                                                <input required onchange="test(`certificate-{{$children['id']}}`)"  name="files[certificate]" id="certificate-{{$children['id']}}" type="file" data-dz-thumbnail="" style="border-radius: 20px; width:auto;"  />
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
                                                <input required onchange="test(`statement-{{$children['id']}}`)"  name="files[statement]" id="statement-{{$children['id']}}" type="file" data-dz-thumbnail="" style="border-radius: 20px; width:auto;"  />
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
                            <div style="height: 15px"></div>

                            <div style="height: 15px"></div>
                            <div>
                            </div>
                            <div align="center">
                                <div class="text-right mt-5">
                                    <button class="btn btn-warning w-24 mr-1 mb-2">Далі</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@include('js_function')
<script>
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
