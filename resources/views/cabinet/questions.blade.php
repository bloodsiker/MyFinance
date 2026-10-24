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
                        <div class="intro-y box p-5">
                                <div>
                                    <div id="faq-accordion-1" class="accordion p-5">

                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-1" class="accordion-header">

                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-1" aria-expanded="false" aria-controls="faq-accordion-collapse-1">
                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                        <label for="input-wizard-1" class="text-lg form-label">1. Хто може вчитися в дистанційній школі?</label>
                                                        <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                    </div>
                                                </button>

                                            </div>
                                            <div id="faq-accordion-collapse-1" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-1" data-bs-parent="#faq-accordion-1" style="display: none;">
                                                <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                    <div>
                                                        <div style="height: 15px"></div>
                                                        В нашій дистанційній школі може навчатися будь-яка дитина. Вам не знадобиться оформляти додаткові документи або вказувати особливі причини. Школярі мають право вільно обирати форму і темп отримання освіти, навчальний заклад, методи і засоби навчання; безпечні і нешкідливі умови навчання, на повазі до людської гідності (Закон України про освіту, Стаття 53).
                                                        <div style="height: 15px"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-2" class="accordion-header">

                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-2" aria-expanded="false" aria-controls="faq-accordion-collapse-2">
                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                        <label for="input-wizard-1" class="text-lg form-label">2. Коли можна перевести дитину в дистанційну школу?</label>
                                                        <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                    </div>
                                                </button>

                                            </div>
                                            <div id="faq-accordion-collapse-2" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-2" data-bs-parent="#faq-accordion-1" style="display: none;">
                                                <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                    <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                        <div>
                                                            <div style="height: 15px"></div>
                                                            Як на початку, так і впродовж навчального року. Учні 9-х і 11-их класів, можуть вступити в нашу школу до кінця I семестру.
                                                            <div style="height: 15px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-3" class="accordion-header">

                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-3" aria-expanded="false" aria-controls="faq-accordion-collapse-3">
                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                        <label for="input-wizard-1" class="text-lg form-label">3. Чи потрібно дитині приїжджати в школу?</label>
                                                        <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                    </div>
                                                </button>

                                            </div>
                                            <div id="faq-accordion-collapse-3" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-3" data-bs-parent="#faq-accordion-1" style="display: none;">
                                                <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                    <div>
                                                        <div style="height: 15px"></div>
                                                        Фізична присутність необхідно лише на ДПА / ЗНО і тільки учням 4-х, 9-х і 11-их класів. Учням інших класів ми рекомендуємо почати з нами навчальний рік на виїзному тренінгу і відсвяткувати його закінчення на Grad Camp.
                                                        <div style="height: 15px"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-4" class="accordion-header">

                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-4" aria-expanded="false" aria-controls="faq-accordion-collapse-4">
                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                        <label for="input-wizard-1" class="text-lg form-label">4. Чи потрібно батькам приїжджати в школу?</label>
                                                        <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                    </div>
                                                </button>

                                            </div>
                                            <div id="faq-accordion-collapse-4" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-4" data-bs-parent="#faq-accordion-1" style="display: none;">
                                                <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                    <div>
                                                        <div style="height: 15px"></div>
                                                        Усi питання ми обговоримо і вирішимо по телефону або онлайн. Ви також завжди можете бути в курсі успішність своєї дитини через його електронний щоденник.
                                                        <div style="height: 15px"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-5" class="accordion-header">

                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-5" aria-expanded="false" aria-controls="faq-accordion-collapse-5">
                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                        <label for="input-wizard-1" class="text-lg form-label">5. Чи може дитина вчитися в школі, перебуваючи за межами України?</label>
                                                        <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                    </div>
                                                </button>

                                            </div>
                                            <div id="faq-accordion-collapse-5" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-5" data-bs-parent="#faq-accordion-1" style="display: none;">
                                                <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                    <div>
                                                        <div style="height: 15px"></div>
                                                        Так. Дистанційна освіта можна продовжувати всюди, де є доступ до інтернету.
                                                        <div style="height: 15px"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-6" class="accordion-header">

                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-6" aria-expanded="false" aria-controls="faq-accordion-collapse-6">
                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                        <label for="input-wizard-1" class="text-lg form-label">6. Мова навчання?</label>
                                                        <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                    </div>
                                                </button>

                                            </div>
                                            <div id="faq-accordion-collapse-6" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-6" data-bs-parent="#faq-accordion-1" style="display: none;">
                                                <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                    <div>
                                                        <div style="height: 15px"></div>
                                                        Українська.
                                                        <div style="height: 15px"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-7" class="accordion-header">

                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-7" aria-expanded="false" aria-controls="faq-accordion-collapse-7">
                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                        <label for="input-wizard-1" class="text-lg form-label">7. Чи може моя дитина брати участь в МАН та олімпіадах?</label>
                                                        <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                    </div>
                                                </button>

                                            </div>
                                            <div id="faq-accordion-collapse-7" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-7" data-bs-parent="#faq-accordion-1" style="display: none;">
                                                <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                    <div>
                                                        <div style="height: 15px"></div>
                                                        Так, за бажанням
                                                        <div style="height: 15px"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-8" class="accordion-header">

                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-8" aria-expanded="false" aria-controls="faq-accordion-collapse-8">
                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                        <label for="input-wizard-1" class="text-lg form-label">8. Я хвилююсь, що моя дитина буде перевантажена. Чи повинен моя дитина брати участь в заходах школи?</label>
                                                        <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                    </div>
                                                </button>

                                            </div>
                                            <div id="faq-accordion-collapse-8" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-8" data-bs-parent="#faq-accordion-1" style="display: none;">
                                                <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                    <div>
                                                        <div style="height: 15px"></div>
                                                        Ні. По бажанню.
                                                        <div style="height: 15px"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <div id="faq-accordion-content-9" class="accordion-header">

                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-9" aria-expanded="false" aria-controls="faq-accordion-collapse-9">
                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                        <label for="input-wizard-1" class="text-lg form-label">9. Чи потрібно мені користуватися послугами супервайзера?</label>
                                                        <svg style="float: right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class=" text-center feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                    </div>
                                                </button>

                                            </div>
                                            <div id="faq-accordion-collapse-9" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-9" data-bs-parent="#faq-accordion-1" style="display: none;">
                                                <div align="center" class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                                    <div>
                                                        <div style="height: 15px"></div>
                                                        Ні. Дану послугу можна вибрати за бажанням від 1 місяця.
                                                        <div style="height: 15px"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item"></div>

                                    </div>

                                </div>
                        </div>

                    </div>

                    </div>
                </div>
            </div>
        </div>


@endsection
