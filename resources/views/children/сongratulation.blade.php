@extends('../layout/' . $layout)

@section('subhead')
    <title>Процес реєстрації</title>
@endsection

@section('subcontent')


<style>
  .thumb-wrap {
  position: relative;
  padding-bottom: 56.25%; /* задаёт высоту контейнера для 16:9 (если 4:3 — поставьте 75%) */
  height: 0;
  overflow: hidden;
}
.thumb-wrap iframe {
  position: absolute;
  top: 5%;
  left: 5%;
  width: 90%;
  height: 90%;
  border-width: 0;
  outline-width: 0;
}

</style>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">

                    <div class="intro-y flex items-center h-10"></div>

                    <div align="center">
                        <div style="width: 83%" class="intro-y box p-5">
                            <form method="get" action="{{route('start-page')}}">
                                @csrf
                                <input name="status" id="pay" style="display: none">
                              
                              
                              
                           
                                <div class="intro-y flex items-center h-10">
                                    <h2 style="width: 100%" class="text-center text-lg font-medium truncate mr-5">Вітаємо!</h2>
                                </div>
                                <div style="height: 15px"></div>
                                <div class="intro-y col-span-12 sm:col-span-4">
                                    <h2 style="width: 100%" class="text-center font-medium mr-5">
                                        На пошту Вашої дитини вже прийшло запрошення приступити до навчання на платформі JAMM24<br>
                                        ПС: перевірте спам ящик 🙂
                                      <!--  Також до Вашої уваги відоогляд особистого кабінету учня на платформі JAMM24, який ми рекомендуємо переглянути.
                                    </h2>
                                  <br>
                                  <br>
                                  <div class="thumb-wrap">
  <iframe width="560" height="315" src="https://www.youtube.com/embed/nZHCXiqIE5c" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>
    -->                                
                                </div>

                                <div style="height: 15px"></div>
                                <h2 style="width: 100%" class="text-center text-lg font-medium truncate mr-5">Завершіть процес реєстрації в школу!</h2>
                                <div style="height: 15px"></div>
                                <div align="center">
                                    <div class="text-right mt-5">
                                        <button class="btn btn-warning w-24 mr-1 mb-2">Далі</button>
                                    </div>
                                </div>
                                <div style="height: 5%"></div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
