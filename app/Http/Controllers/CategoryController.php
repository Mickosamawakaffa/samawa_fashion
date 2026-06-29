<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        return view('categories.index', compact('categories'));
    }
}
