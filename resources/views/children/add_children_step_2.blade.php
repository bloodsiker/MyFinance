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

                    <div class="intro-y box p-5">
                        <form method="post" action="{{route('children.update.step_2', ['id' => $children->id])}}">
                            @csrf
                            <div  class="intro-y flex items-center h-20">
                                <h2 style="width: 100%" class="text-2xl font-semibold">Моя дитина забезпечена всім необхідним для навчання в JAMM School</h2>
                            </div>

                            <div class="mb-10">
                                <div class="flex items-center mt-4">
                                    <input type="checkbox" class="custom-checkbox shadow-sm" name="internet" id="internet" />
                                    <label for="internet" class="cursor-pointer ml-2 fz-16">Доступ до мережі інтернет</label>
                                </div>
                                <div class="flex items-center mt-4">
                                    <input type="checkbox" class="custom-checkbox shadow-sm" name="computer" id="computer" value="computer" />
                                    <label for="computer" class="cursor-pointer ml-2 fz-16">Компʼютером або планшетом</label>
                                </div>
                                <div class="flex items-center mt-4">
                                    <input type="checkbox" class="custom-checkbox shadow-sm" name="zoom" id="zoom" />
                                    <label for="zoom" class="cursor-pointer ml-2 fz-16">Учнівським Google акаунтом</label>
                                </div>
                                <div class="flex items-center mt-4">
                                    <input type="checkbox" class="custom-checkbox shadow-sm" name="camera" id="camera" />
                                    <label for="camera" class="cursor-pointer ml-2 fz-16">На пристрої для навчання є веб камера та мікрофон</label>
                                </div>
                            </div>

                            <div class="alert alert-secondary show mb-2" role="alert">
                                <div class="flex items-center">
                                    <div class="font-medium text-lg flex" style="align-items: baseline;">
                                        <span style="color: #2d3748; font-size: 21px">Важливо!</span>
                                    </div>
                                </div>
                                <div class="mt-3 fz-16 color-text">Ми не рекомендуємо використовувати для навчання смартфони, також для навчання необхідний стабільний доступ до Інтернету.</div>
                                <div class="mt-3 fz-16 color-text">Для того, щоб учень мав можливість дивитись матеріал, який ми пропонуємо, без реклами, рекомендуємо користуватись
                                    YouTube Premium чи встановити плагін AdBlock до веббраузеру.</div>
                                <div class="mt-3 fz-16 color-text">В іншому випадку школа не несе відповідальності за якість послуг, що надаються.</div>
                                <br>
                                <hr>
                                <div class="flex items-center mt-4">
                                    <input type="checkbox" class="custom-checkbox shadow-sm" id="allow_rules" value=""/>
                                    <label for="allow_rules" class="cursor-pointer ml-2 fz-16 color-text">Погоджуюсь</label>
                                </div>
                            </div>

                            <br>
                            <br>
                            <hr>
                            <br>
                            <div class="text-right mt-5">
                                <button class="btn btn-warning w-24 mr-1 mb-2 btn-disabled" id="clickNext" disabled>Далі</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#allow_rules").on("click", function() {
                let btn = $('#clickNext');
                if ($(this).is(":checked")) {
                    btn.removeAttr("disabled");
                    btn.removeClass('btn-disabled');
                } else {
                    btn.attr("disabled", "disabled");
                    btn.addClass('btn-disabled');
                }
            });
        });
    </script>

    <style>
        .custom-checkbox {
            border-radius: 4px!important;
            width: 20px!important;
            height: 20px!important;
        }
        .btn-disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }
        :before,:after {
            --tw-border-spacing-x: 0;
            --tw-border-spacing-y: 0;
            --tw-translate-x: 0;
            --tw-translate-y: 0;
            --tw-rotate: 0;
            --tw-skew-x: 0;
            --tw-skew-y: 0;
            --tw-scale-x: 1;
            --tw-scale-y: 1;
            --tw-pan-x: ;
            --tw-pan-y: ;
            --tw-pinch-zoom: ;
            --tw-scroll-snap-strictness: proximity;
            --tw-ordinal: ;
            --tw-slashed-zero: ;
            --tw-numeric-figure: ;
            --tw-numeric-spacing: ;
            --tw-numeric-fraction: ;
            --tw-ring-inset: ;
            --tw-ring-offset-width: 0px;
            --tw-ring-offset-color: #fff;
            --tw-ring-color: rgb(59 130 246 / .5);
            --tw-ring-offset-shadow: 0 0 #0000;
            --tw-ring-shadow: 0 0 #0000;
            --tw-shadow: 0 0 #0000;
            --tw-shadow-colored: 0 0 #0000;
        }

        .shadow-sm {
            --tw-shadow: 0 1px 2px 0 rgb(0 0 0 / .05);
        }

        [type=checkbox],[type=radio] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            display: inline-block;
            vertical-align: middle;
            background-origin: border-box;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            flex-shrink: 0;
            height: 1rem;
            width: 1rem;
            color: #96D038;
            background-color: #fff;
            border-color: #6b7280;
            border-width: 1px;
            --tw-shadow: 0 0 #0000
        }

        [type=checkbox] {
            border-radius: 0
        }

        [type=radio] {
            border-radius: 100%
        }

        [type=checkbox]:focus,[type=radio]:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)
        }

        [type=checkbox]:checked,[type=radio]:checked {
            border-color: transparent;
            background-color: currentColor;
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat
        }

        [type=checkbox]:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e")
        }

        [type=radio]:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e")
        }

        [type=checkbox]:checked:hover,[type=checkbox]:checked:focus,[type=radio]:checked:hover,[type=radio]:checked:focus {
            border-color: transparent;
            background-color: currentColor
        }
        .fz-16 {
            font-size: 16px;
        }
        .link_under {
            text-decoration: underline;
        }
        .color-text {
            color: #4A5568;
        }
    </style>

@endsection
