<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting\SettingGeneral;
use Illuminate\Http\Request;


class SettingGeneralController extends Controller
{
    public function list()
    {
        $list = SettingGeneral::all();

        return view('admin/settings/general/list', compact('list'));
    }

    public function updateTrainingFormat(Request $request)
    {
        try {
            $setting = SettingGeneral::where('key', 'training_format')->first();
            $setting->value = $request->get('training_format');
            $setting->save();

            return redirect()->back()->with("success", "Налаштування оновлені");

        } catch (\Exception $exception) {
            return redirect()->back()->with("error", $exception->getMessage());
        }
    }
}
