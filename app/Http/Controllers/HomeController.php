<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)->orderBy('sort_order')->get();
        $categories = Category::where('is_active', true)->take(6)->get();
        $newProducts = Product::where('is_active', true)
            ->where('is_new_arrival', true)
            ->with('category')
            ->take(8)
            ->get();
        $bestSellers = Product::where('is_active', true)
            ->where('is_best_seller', true)
            ->with('category')
            ->take(8)
            ->get();
        $testimonials = Testimonial::where('is_approved', true)->take(6)->get();

        return view('home', compact(
            'banners',
            'categories',
            'newProducts',
            'bestSellers',
            'testimonials'
        ));
    }
}
