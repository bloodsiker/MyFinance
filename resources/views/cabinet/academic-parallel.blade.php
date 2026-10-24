<div class="col-span-12">
    <div class="intro-y box xl:col-span-8">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
            <h2 class="font-medium text-base mr-auto">Формат навчання - <span style="color: #1e2dec">{{$child['training_format']['format_name']}}</span></h2>
            <div id="anchor" class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                <a href="#anchor" "{{--route('change-learning-format', ['child' => $child['id']])--}}" class="btn btn-warning w-24 mr-1 mb-2">
                    Змінити
                </a>
            </div>
        </div>

        <div class="p-5" id="hoverable-table">
            <div class="preview" style="display: block; opacity: 1;">
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
                    @foreach($child['training_format']['table_data'] as $subject_id => $semesters)
                        <tr class="hover:bg-gray-200">
                            <td data-label="subject" class="border">
                                {{$subjects_list[$subject_id]['name']}}
                            </td>
                            @for($s = 1; $s <= 2; $s++)
                                @for($t = 1; $t <= 4; $t++)
                                    @php
                                        if(isset($semesters[$s][$t]))
                                        {
                                            $deadline = $semesters[$s][$t];
                                            $deadline_id = 'd-'.$deadline['id'];
                                        } else {
                                            $deadline = [
                                                'date' => ''
                                            ];
                                            $deadline_id = $subject_id.'-'.$s.'-'.$t;
                                        }
                                    @endphp
                                    {{--$subject_id.'-'.$s.'-'.$t--}}
                                    <td data-label="{{$deadline_id}}" class="border">
                                        {{$deadline['date']}}
                                    </td>
                                @endfor

                            @endfor
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div id="blank-slide-over" class="p-5">
            <div class="preview">
                <p>Змінювати формат можна один раз на півріччя</p>
            </div>
        </div>
    </div>
</div>
