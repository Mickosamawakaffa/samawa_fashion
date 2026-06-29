<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Category Filter
        if ($request->filled('category')) {
            $category = $request->category;
            if (is_array($category)) {
                $query->whereIn('category_id', $category);
            } else {
                $query->where('category_id', $category);
            }
        }

        // Price Filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort Options
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'bestseller':
                    $query->withCount(['orderItems as total_sales' => function ($q) {
                        $q->select(DB::raw('sum(quantity)'));
                    }])->orderByRaw('CASE WHEN total_sales IS NULL THEN 0 ELSE total_sales END DESC');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        
        // Cache Category Query
        $categories = Cache::remember('categories_active', 3600, function () {
            return Category::where('is_active', true)->get();
        });

        // Calculate max product price for the price range slider default
        $maxPrice = Product::where('is_active', true)->max('price') ?? 5000000;

        if ($request->ajax()) {
            return response()->json([
                'html' => view('products._grid', compact('products'))->render()
            ]);
        }

        return view('products.index', compact('products', 'categories', 'maxPrice'));
    }

    public function show(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['category', 'images'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->firstOrFail();

        // Check if user is eligible to write a review
        $canReview = false;
        if (auth()->check()) {
            $hasOrdered = \App\Models\Order::where('user_id', auth()->id())
                ->where('status', 'delivered')
                ->whereHas('items', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                })
                ->exists();
            
            $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();

            $canReview = $hasOrdered && !$hasReviewed;
        }

        // Fetch paginated reviews list
        $reviewsQuery = $product->reviews()->with('user')->orderBy('created_at', 'desc');

        if ($request->filled('rating') && $request->rating !== 'all') {
            $reviewsQuery->where('rating', $request->rating);
        }

        $reviews = $reviewsQuery->paginate(5);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->take(4)
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('products._reviews', compact('reviews'))->render()
            ]);
        }

        return view('products.show', compact('product', 'relatedProducts', 'canReview', 'reviews'));
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $product = Product::findOrFail($id);

        // Check review eligibility again
        $hasOrdered = \App\Models\Order::where('user_id', auth()->id())
            ->where('status', 'delivered')
            ->whereHas('items', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->exists();
        
        $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->exists();

        if (!$hasOrdered || $hasReviewed) {
            return redirect()->back()->with('error', 'Anda tidak diizinkan untuk memberikan ulasan pada produk ini.');
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'review-' . auth()->id() . '-' . time() . '.' . $file->getClientOriginalExtension();
            
            // Compress using Intervention Image Manager v3
            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $image = $manager->read($file);
            $image->scale(width: 600);
            
            $path = storage_path('app/public/reviews/' . $filename);
            if (!file_exists(storage_path('app/public/reviews'))) {
                mkdir(storage_path('app/public/reviews'), 0755, true);
            }
            $image->save($path);
            $photoPath = 'reviews/' . $filename;
        }

        \App\Models\Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'photo' => $photoPath,
        ]);

        return redirect()->back()->with('success', 'Ulasan Anda berhasil dikirim!');
    }
}
