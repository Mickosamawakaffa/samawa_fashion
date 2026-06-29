<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)->orderBy('sort_order')->get();
        $categories = Category::where('is_active', true)->get();
        $newProducts = Product::where('is_active', true)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
            
        // Get products with total order quantity
        $bestSellers = Product::where('is_active', true)
            ->withCount(['orderItems as total_sales' => function ($query) {
                $query->select(DB::raw('sum(quantity)'));
            }])
            ->with('category')
            ->orderByRaw('CASE WHEN total_sales IS NULL THEN 0 ELSE total_sales END DESC')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
            
        $testimonials = Testimonial::where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('home', compact(
            'banners',
            'categories',
            'newProducts',
            'bestSellers',
            'testimonials'
        ));
    }
}
