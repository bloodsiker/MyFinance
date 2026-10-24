
@extends('../layout/' . $layout)


@section('subhead')
    <title>Статистика успішності</title>
@endsection

@section('subcontent')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center h-10"></div>
                    <div align="center">
                        <div class="grid grid-cols-12 gap-2">

                            <div class="col-span-12">
                                <div class="intro-y box mt-5 ">
                                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                        <h2 class="font-medium text-base mr-auto">Статистика успішності</h2>
                                        <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                                            {{--<a href="" class="btn btn-warning w-24 mr-1 mb-2">
                                                Змінити
                                            </a>--}}
                                        </div>
                                    </div>
                                    <div class="p-5" id="hoverable-table">
                                        <div class="preview" style="display: block; opacity: 1;">
                                            <div class="overflow-x-auto">
                                                <table class="table resp-tab">
                                                    <thead>
                                                    <tr>
                                                        {{-- <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">#</th> --}}
                                                        <th data-label="gредмет" class="border border-b-2 dark:border-dark-5 whitespace-nowrap">предмет</th>
                                                        <th data-label="за минулий рік" class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Завдання</th>
                                                        <th data-label="поточна оцінка" class="border border-b-2 dark:border-dark-5 whitespace-nowrap">поточна оцінка</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($grades as $sub_group)
                                                        <tr class="hover:bg-gray-200">
                                                            <td class="border">{{$sub_group['subject']['name']}}</td>
                                                        </tr>
                                                        @foreach($sub_group['grades'] as $grade_item)
                                                        <tr>
                                                            {{-- <td class="border">{{$loop->index+1}}</td> --}}
                                                            <td class="border"></td>
                                                            <td data-label="item-name-{{$grade_item['id']}}" class="border">{{$grade_item['itemname']}}</td>
                                                            <td data-label="item-grade-{{$grade_item['id']}}" class="border">{{$grade_item['gradeformatted']}}</td>
                                                        </tr>
                                                        @endforeach
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
