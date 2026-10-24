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
                            <form action="{{route('post_create')}}" enctype="multipart/form-data" method="post">
                                @csrf
                                <div class="post__content tab-content">
                                    <div id="content" class="tab-pane p-5 active" role="tabpanel" aria-labelledby="content-tab">
                                        <div class="border border-gray-200 dark:border-dark-5 rounded-md p-5 mt-5">
                                            <div class="mt-5">
                                                <div>
                                                    <label for="post-name-7" class="form-label">Заголовок</label>
                                                    <input required id="post-name-7" name="name" type="text" class="form-control" placeholder="Введiть заголовок">
                                                </div>
                                                <div>
                                                    <label for="post-post-7" class="form-label">Вмiст</label>
                                                    <textarea required rows="15" id="post-post-7" name="post" type="text" class="form-control" placeholder="Введiть вмiст"></textarea>
                                                </div>
                                                <div class="mt-3">
                                                    <label for="file">
                                                        <div class="border-2 border-dashed dark:border-dark-5 rounded-md pt-4">
                                                            <div class="flex flex-wrap px-4">
                                                                <div style="display: none" class="w-24 h-24 relative image-fit mb-5 mr-5 cursor-pointer zoom-in">
                                                                    {{--<img class="rounded-md" alt="Rubick Tailwind HTML Admin Template" src="https://rubick-laravel.left4code.com/dist/images/preview-12.jpg">--}}
                                                                </div>
                                                                <div class="px-4 pb-4 flex items-center cursor-pointer relative">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image w-4 h-4 mr-2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg> Завантажте картинку
                                                                    <input {{--onchange="fileView()"--}} id="file" name="file" type="file" class="w-full h-full top-0 left-0 absolute opacity-0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-warning w-24 inline-block mr-1 mb-2">Створити</button>
                            </form>
                        </div>
                        <div style="height: 30px"></div>
                    @endif
                    <div>
                        @foreach($item as $news)
                            <div class="intro-y col-span-12 md:col-span-6 xl:col-span-4 box">
                                @if (Auth::user()->role_id > 1)
                                <div class="flex items-center border-b border-gray-200 dark:border-dark-5 px-5 py-4">
                                    <div class="w-10 h-10 flex-none image-fit"></div>
                                    <div class="ml-3 mr-auto"></div>
                                    <div class="dropdown ml-3">
                                        <a href="javascript:;" class="dropdown-toggle w-5 h-5 text-gray-600 dark:text-gray-300" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical w-4 h-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                        </a>
                                        <div class="dropdown-menu w-40">
                                            <div class="dropdown-menu__content box dark:bg-dark-1 p-2">
                                                <form action="{{route('post_delete')}}" method="post">
                                                    <button name="id" value="{{$news->id}}" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash w-4 h-4 mr-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Видалити Пост
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="p-5">
                                    <div>
                                        <img alt="Rubick Tailwind HTML Admin Template" class="rounded-md" src="{{$news->file??'https://parent.jammschool.com//dist/images/preview-7.jpg'}}">
                                    </div>
                                    <a class="block font-medium text-base mt-5">{{$news->name}}</a>
                                    <div class="text-gray-700 dark:text-gray-600 mt-2">{{$news->post}}</div>
                                </div>
                            </div>
                        @endforeach

                    </div>



                </div>

            </div>
        </div>
    </div>
</div>

@endsection
