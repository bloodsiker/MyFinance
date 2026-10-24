@extends('layout/' . $layout)
@section('subhead')
<title>Налаштування</title>
@endsection

@section('subcontent')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 ">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10"></div>
                <div align="center">
                    <div>
                        <div class="intro-y col-span-12 md:col-span-6 xl:col-span-4 box">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="border-b-2 dark:border-dark-5">Налаштування</th>
                                    <th class="border-b-2 dark:border-dark-5 whitespace-nowrap"></th>
                                </tr>
                                </thead>
                                    <tbody>
                                    <tr>
                                        <td class="border-b dark:border-dark-5">Загальні налаштування</td>
                                        <td class="border-b dark:border-dark-5 text-right">
                                            <a class="btn btn-xs btn-primary" href="{{ route('admin.settings.general.list') }}">Відкрити</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-b dark:border-dark-5">MasterPass</td>
                                        <td class="border-b dark:border-dark-5 text-right">
                                            <a class="btn btn-xs btn-primary" href="{{ route('admin.settings.master_pass.list') }}">Відкрити</a>
                                        </td>
                                    </tr>
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
