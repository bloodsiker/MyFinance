@extends('../admin/manage-learning-formats')

@section('editing_format_form')
    @if(isset($format_id))
<form method="post" action="{{route('manage-learning-formats')}}" enctype="multipart/form-data">
    <input type="hidden" name="format-id" value="{{$format_id}}">
    <input type="hidden" name="format-type" value="{{$format_type}}">
    <div class="d-flex format-inputs">
        <div class="form-group mr-3">
            {{--<label for="learningFormarInput">Змінити назву формату</label>
            <input type="text" class="form-control form-control-sm" id="learningFormarInput" placeholder="формат">--}}
            <label for="learningFormatNameView">Назва: <input id="learningFormatNameEdit" class="form-control" type="text" value="{{$format_name}}" name="format-name"></label>
            <label for="learningFormatCodeView">Умовний код: <input id="learningFormatCodeView" class="form-control" type="text" value="{{$format_code}}" name="format-code"></label>
            <label for="learningFormatSelect">Оберiть клас</label>
            <select class="form-select form-select-sm" id="learningFormatClassSelect" name="format-class">
                @for($cl = 5; $cl <= 11; $cl++)
                <option value="{{$cl}}"
                        @if($cl == $format_class)
                            selected="selected"
                        @endif
                >{{$cl}}й клас</option>
                @endfor
            </select>
        </div>
    </div>
    <div id="faq-accordion-1" class="accordion p-5">
        <div class="accordion-item">
            <div id="faq-accordion-collapse-3" aria-labelledby="faq-accordion-content-3"
                 data-bs-parent="#faq-accordion-1" style="display: block;">
                <div class="text-gray-700 dark:text-gray-600 leading-relaxed">
                    <div style="height: 15px"></div>
                    <div align="center"
                         class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                        <div>
                            <div id="format-{{$format_id}}" class="">

                                <div class="intro-y box mt-5 ">

                                    <div class="p-5" id="hoverable-table">
                                        <div class="preview" style="display: block; opacity: 1;">
                                            <div class="container">
                                                <table class = "resp-tab">
                                                    <thead>
                                                    <tr>
                                                        <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap" rowspan="2">предмет</th>
                                                        <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap" colspan="4">I семестр</th>
                                                        <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap" colspan="4">II семестр</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">тема 1</th>
                                                        <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">тема 2</th>
                                                        <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">тема 3</th>
                                                        <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">тема 4</th>
                                                        <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">тема 1</th>
                                                        <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">тема 2</th>
                                                        <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">тема 3</th>
                                                        <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">тема 4</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($table_data as $subject_id => $semesters)
                                                        <tr class="hover:bg-gray-200">
                                                            <td data-label="subject" class="border">
                                                                <select class="form-select form-select-sm" name="subjects[{{$subject_id}}]">
                                                                    <option>Обрати зі списку</option>
                                                                    @foreach($subjects_list as $sub)
                                                                        <option
                                                                            value="{{$sub['id']}}"
                                                                            @if($sub['id'] == $subject_id)
                                                                                selected
                                                                            @endif
                                                                        >
                                                                            {{$sub['name']}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                {{--config('constant.'.$subject)--}}
                                                                {{--<input type="hidden" name="subject-name" value="{{$subject}}">--}}
                                                            </td>
                                                            @for($s = 1; $s <= 2; $s++)
                                                                @for($t = 1; $t <= 4; $t++)
                                                                    @php
                                                                        if(isset($semesters[$s][$t]))
                                                                        {
                                                                            $deadline = $semesters[$s][$t];
																			$deadline_id = $deadline['id'];
                                                                            $input_name = "deadlines[$subject_id][$deadline_id]";
                                                                        }
																		else {
																			$deadline = [
																			    'date' => ''
																			];
                                                                            $input_name = "deadlines[$subject_id][$s][$t]";
																		}
                                                                    @endphp
                                                                    {{--$subject_id.'-'.$s.'-'.$t--}}
                                                                    <td data-label="{{$input_name}}" class="border">
                                                                        <input
                                                                            name="{{$input_name}}"
                                                                            data-label="{{$input_name}}"
                                                                            type="date"
                                                                            value="{{$deadline['date']}}"
                                                                        />
                                                                    </td>
                                                                @endfor

                                                            @endfor
                                                        </tr>
                                                    @endforeach

                                                    <tr class="hover:bg-gray-200">
                                                        <td data-label="new_subject" class="border">
                                                            <select class="form-select form-select-sm" name="new-subject_id">
                                                                <option>Обрати зі списку</option>
                                                                @foreach($subjects_list as $sub)
                                                                    <option
                                                                        value="{{$sub['id']}}"
                                                                    >
                                                                        {{$sub['name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        @for($s = 1; $s <= 2; $s++)
                                                            @for($t = 1; $t <= 4; $t++)

                                                                <td data-label="{{'new-'.$s.'-'.$t}}" class="border">
                                                                    <input
                                                                        name="{{'new-'.$s.'-'.$t}}"
                                                                        data-label="{{'new-'.$s.'-'.$t}}"
                                                                        type="date"
                                                                        value=""
                                                                    />
                                                                </td>
                                                            @endfor

                                                        @endfor
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="height: 15px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div> {{--Учеба--}}
        <div class="accordion-item"></div>
    </div>
    <div align="center">
        <div class="text-right mt-5">
            <a class="btn btn-danger w-24 mr-1 mb-2" href="{{route('delete-format', ['id' => $format_id])}}">Видалити</a>
            <button class="btn btn-warning w-24 mr-1 mb-2">Зберегти</button>
        </div>
    </div>
</form>
    @endif
@endsection
