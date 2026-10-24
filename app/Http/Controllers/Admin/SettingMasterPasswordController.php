<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting\MasterPassword;
use Illuminate\Http\Request;


class SettingMasterPasswordController extends Controller
{
    public function list(Request $request)
    {
        $list = MasterPassword::all();

        return view('admin/settings/master_password/list', compact('list'));
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            $data = $request->all();
            $data['is_active'] = $request->is_active ? '1' : '0';

            $item = new MasterPassword();
            $item->fill($data);
            $item->fill($request->all());
            $item->save();

            return redirect()->route('admin.settings.master_pass.list')->with("success", "Новий пароль додано");
        }

        return view('admin/settings/master_password/create');
    }

    public function edit(Request $request, $id)
    {
        $item = MasterPassword::findOrFail($id);

        if ($request->isMethod('POST')) {
            $data = $request->all();
            $data['is_active'] = $request->is_active ? '1' : '0';
            $item->fill($data);
            $item->save();

            return redirect()->route('admin.settings.master_pass.list')->with("success", "Пароль змінений");
        }

        return view('admin/settings/master_password/edit', compact('item'));
    }

    public function delete($id)
    {
        $item = MasterPassword::findOrFail($id);

        $item->delete();

        return redirect()->route('admin.settings.master_pass.list')->with("success", "Пароль видалено");
    }
}
