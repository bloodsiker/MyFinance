<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('categories', compact('categories'));
    }

    public function create(Request $request)
    {
        $category = new Category();
        $category->name = $request->get('name');
        $category->save();

        return redirect()->back()->with('success', 'Категорію додано');
    }
}
