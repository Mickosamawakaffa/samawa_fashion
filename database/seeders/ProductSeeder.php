<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Seed 10 new products (5 Atasan/Dress/Outer, 5 Bawahan)
     * with local dummy fashion photos. Does NOT delete existing data.
     */
    public function run(): void
    {
        $atasanCat = Category::where('slug', 'atasan')->first();
        $dressCat  = Category::where('slug', 'dress')->first();
        $outerCat  = Category::where('slug', 'outer')->first();
        $bawahanCat = Category::where('slug', 'bawahan')->first();

        // Fallback: if specific categories don't exist, use first available
        if (!$atasanCat) $atasanCat = Category::first();
        if (!$dressCat) $dressCat = $atasanCat;
        if (!$outerCat) $outerCat = $atasanCat;
        if (!$bawahanCat) $bawahanCat = Category::first();

        $products = [
            // ===== BAJU (5 items) =====
            [
                'name' => 'Kemeja Linen Putih',
                'category' => $atasanCat,
                'price' => 450000,
                'stock' => 15,
                'desc' => 'Kemeja linen premium berwarna putih bersih, cocok untuk gaya kasual maupun semi-formal. Bahan adem dan ringan, ideal untuk cuaca tropis.',
                'images' => ['products/dummy/kemeja-putih-1.jpg', 'products/dummy/kemeja-putih-2.jpg'],
            ],
            [
                'name' => 'Blouse Sutra Krem',
                'category' => $atasanCat,
                'price' => 650000,
                'stock' => 12,
                'desc' => 'Blouse sutra premium warna krem dengan detail kerah yang elegan. Tekstur halus dan lembut di kulit, memberikan kesan mewah.',
                'images' => ['products/dummy/blouse-krem-1.jpg', 'products/dummy/blouse-krem-2.jpg'],
            ],
            [
                'name' => 'Dress Maxi Hitam',
                'category' => $dressCat,
                'price' => 1200000,
                'stock' => 10,
                'desc' => 'Gaun maxi hitam klasik yang tak lekang oleh waktu. Potongan longgar yang mengalir, sempurna untuk acara makan malam hingga pesta.',
                'images' => ['products/dummy/dress-hitam-1.jpg', 'products/dummy/dress-hitam-2.jpg'],
            ],
            [
                'name' => 'Outer Blazer Coklat',
                'category' => $outerCat,
                'price' => 950000,
                'stock' => 18,
                'desc' => 'Blazer coklat berpotongan modern dengan struktur bahu yang tegas. Cocok dipadukan dengan celana panjang atau rok untuk kesan profesional.',
                'images' => ['products/dummy/blazer-coklat-1.jpg', 'products/dummy/blazer-coklat-2.jpg'],
            ],
            [
                'name' => 'Kaos Polos Premium',
                'category' => $atasanCat,
                'price' => 280000,
                'stock' => 20,
                'desc' => 'Kaos polos berbahan katun combed 30s premium. Lembut, tidak berbulu, dan tahan dicuci berkali-kali tanpa pudar.',
                'images' => ['products/dummy/kaos-polos-1.jpg'],
            ],

            // ===== CELANA / BAWAHAN (5 items) =====
            [
                'name' => 'Celana Kulot Linen',
                'category' => $bawahanCat,
                'price' => 380000,
                'stock' => 14,
                'desc' => 'Celana kulot berbahan linen yang nyaman dan adem. Model wide-leg yang memberikan tampilan santai namun tetap modis.',
                'images' => ['products/dummy/celana-kulot-1.jpg'],
            ],
            [
                'name' => 'Celana Palazzo Hitam',
                'category' => $bawahanCat,
                'price' => 420000,
                'stock' => 16,
                'desc' => 'Celana palazzo hitam dengan pinggang karet elastis. Potongan lebar yang jatuh sempurna, cocok untuk daily outfit maupun kerja.',
                'images' => ['products/dummy/celana-palazzo-1.jpg'],
            ],
            [
                'name' => 'Celana Bahan Formal',
                'category' => $bawahanCat,
                'price' => 480000,
                'stock' => 11,
                'desc' => 'Celana bahan formal berkualitas tinggi dengan lipatan garis tengah yang tajam. Pas untuk tampilan corporate dan meeting penting.',
                'images' => ['products/dummy/celana-formal-1.jpg'],
            ],
            [
                'name' => 'Celana Jeans Highwaist',
                'category' => $bawahanCat,
                'price' => 520000,
                'stock' => 13,
                'desc' => 'Jeans highwaist dengan warna indigo gelap yang timeless. Potongan slim-straight memberikan siluet tubuh yang ramping dan proporsional.',
                'images' => ['products/dummy/celana-jeans-1.jpg'],
            ],
            [
                'name' => 'Rok Midi Plisket',
                'category' => $bawahanCat,
                'price' => 390000,
                'stock' => 19,
                'desc' => 'Rok midi plisket dengan lipatan halus yang anggun. Material polyester berkualitas yang tidak mudah kusut dan selalu terlihat rapi.',
                'images' => ['products/dummy/rok-midi-1.jpg'],
            ],
        ];

        foreach ($products as $p) {
            $images = $p['images'] ?? ['products/dummy/default-fashion.jpg'];
            $mainImage = $images[0];

            $product = Product::create([
                'name' => $p['name'],
                'slug' => Str::slug($p['name']) . '-' . time() . '-' . rand(100, 999),
                'category_id' => $p['category']->id,
                'description' => $p['desc'],
                'price' => $p['price'],
                'discount' => 0,
                'stock' => $p['stock'],
                'weight' => 500,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Hitam', 'Putih', 'Krem'],
                'image' => $mainImage,
                'is_active' => true,
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_best_seller' => false,
                'is_dummy' => true,
            ]);

            // Create ProductImage gallery records
            foreach ($images as $index => $path) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => ($index === 0),
                    'sort_order' => $index,
                ]);
            }
        }

        $this->command->info('✅ 10 produk dummy baru (5 baju + 5 celana) berhasil ditambahkan menggunakan foto lokal!');
    }
}
