<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter dummy products
        if ($request->has('dummy') && $request->dummy == '1') {
            $query->where('is_dummy', true);
        }

        $products = $query->orderByDesc('created_at')->paginate(10);
        $dummyCount = Product::where('is_dummy', true)->count();

        return view('admin.products.index', compact('products', 'dummyCount'));
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
            'images' => 'required|array|min:3|max:6',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:3072',
            'primary_image' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $uploadedPaths = [];
            $primaryIndex = (int) $request->primary_image;

            // Upload and compress all images first
            foreach ($request->file('images') as $index => $imageFile) {
                $uploadedPaths[] = $this->compressAndStore($imageFile);
            }

            // Use primary image as the legacy `image` column
            $mainImagePath = $uploadedPaths[$primaryIndex] ?? $uploadedPaths[0];

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
                'image' => $mainImagePath,
                'is_active' => $request->is_active ?? true,
                'is_best_seller' => $request->is_best_seller ?? false,
                'is_new_arrival' => $request->is_new_arrival ?? false,
                'is_featured' => $request->is_featured ?? false,
                'is_dummy' => false,
            ]);

            // Create ProductImage records
            foreach ($uploadedPaths as $index => $path) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => ($index === $primaryIndex),
                    'sort_order' => $index,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            // Clean up any uploaded files
            foreach ($uploadedPaths ?? [] as $path) {
                Storage::disk('public')->delete($path);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan produk: ' . $e->getMessage());
        }
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
            'images' => 'nullable|array|max:6',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:3072',
            'is_active' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
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

            // Handle deleted images
            if ($request->has('delete_images')) {
                foreach ($request->delete_images as $imageId) {
                    $img = ProductImage::find($imageId);
                    if ($img && $img->product_id === $product->id) {
                        // Only delete local files, not external URLs
                        if (!str_starts_with($img->image_path, 'http')) {
                            Storage::disk('public')->delete($img->image_path);
                        }
                        $img->delete();
                    }
                }
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                $existingCount = $product->images()->count();
                $newCount = count($request->file('images'));

                if (($existingCount + $newCount) > 6) {
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('error', 'Maksimal 6 foto per produk. Hapus foto lama terlebih dahulu.');
                }

                foreach ($request->file('images') as $index => $imageFile) {
                    $imagePath = $this->compressAndStore($imageFile);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'is_primary' => false,
                        'sort_order' => $existingCount + $index,
                    ]);
                }

                // If uploading new photos, auto-unset dummy flag
                if ($product->is_dummy) {
                    $data['is_dummy'] = false;
                }
            }

            // Handle primary image selection
            if ($request->has('primary_image_id')) {
                $product->images()->update(['is_primary' => false]);
                $primaryImg = ProductImage::find($request->primary_image_id);
                if ($primaryImg && $primaryImg->product_id === $product->id) {
                    $primaryImg->update(['is_primary' => true]);
                    // Sync legacy image column
                    $data['image'] = $primaryImg->image_path;
                }
            }

            $product->update($data);

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        // Eloquent handles SoftDeletes. Do NOT delete files from storage during soft deletes.
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dinonaktifkan (soft delete)');
    }

    /**
     * Delete a single product image (AJAX).
     */
    public function destroyImage(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Only delete local files, not external URLs
        if (!str_starts_with($image->image_path, 'http')) {
            Storage::disk('public')->delete($image->image_path);
        }

        $wasPrimary = $image->is_primary;
        $image->delete();

        // If deleted image was primary, set another as primary
        if ($wasPrimary) {
            $newPrimary = $product->images()->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
                $product->update(['image' => $newPrimary->image_path]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus']);
    }

    /**
     * Clear all dummy images for a product and mark it as non-dummy.
     */
    public function clearDummyImages(Product $product)
    {
        foreach ($product->images as $image) {
            // Only delete local files, not external URLs
            if (!str_starts_with($image->image_path, 'http')) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }

        $product->update([
            'is_dummy' => false,
            'image' => null,
        ]);

        return redirect()->back()->with('success', 'Semua foto dummy berhasil dihapus. Silakan upload foto asli.');
    }

    /**
     * Compress image using Intervention Image Manager v3
     * Resize max 1200px, generate unique filename.
     */
    private function compressAndStore($file)
    {
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = Str::random(20) . '_' . time() . '.' . $extension;
        
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        
        if ($image->width() > 1200) {
            $image->scale(width: 1200);
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
