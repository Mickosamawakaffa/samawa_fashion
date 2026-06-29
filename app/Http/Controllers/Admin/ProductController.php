<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderByDesc('created_at')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Compress and store main image
        $mainImagePath = $this->compressAndStore($request->file('main_image'));

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'discount' => $request->discount ?? 0,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'sizes' => $request->sizes,
            'colors' => $request->colors,
            'image' => $mainImagePath, // Correct database column listing
            'is_active' => $request->is_active ?? true,
            'is_best_seller' => $request->is_best_seller ?? false,
            'is_new_arrival' => $request->is_new_arrival ?? false,
            'is_featured' => $request->is_featured ?? false,
        ]);

        if ($request->has('images')) {
            foreach ($request->file('images') as $imageFile) {
                $imagePath = $this->compressAndStore($imageFile);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath, // Correct column mapping
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        $product->load('category', 'images');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'discount' => $request->discount ?? 0,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'sizes' => $request->sizes,
            'colors' => $request->colors,
            'is_active' => $request->is_active ?? true,
            'is_best_seller' => $request->is_best_seller ?? false,
            'is_new_arrival' => $request->is_new_arrival ?? false,
            'is_featured' => $request->is_featured ?? false,
        ];

        if ($request->hasFile('main_image')) {
            $data['image'] = $this->compressAndStore($request->file('main_image'));
        }

        $product->update($data);

        if ($request->has('images')) {
            foreach ($request->file('images') as $imageFile) {
                $imagePath = $this->compressAndStore($imageFile);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath, // Correct column mapping
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        // Eloquent handles SoftDeletes. Do NOT delete files from storage during soft deletes.
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dinonaktifkan (soft delete)');
    }

    /**
     * Compress image using Intervention Image Manager v3
     */
    private function compressAndStore($file)
    {
        $filename = 'prod-' . uniqid() . '-' . time() . '.jpg';
        
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        
        if ($image->width() > 800) {
            $image->scale(width: 800);
        }
        
        $destDir = storage_path('app/public/products');
        if (!file_exists($destDir)) {
            mkdir($destDir, 0755, true);
        }
        
        // Save as JPEG with 80% quality
        $image->toJpeg(80)->save($destDir . '/' . $filename);
        
        return 'products/' . $filename;
    }
}
