<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setup Stream Context to avoid Windows/Laragon SSL issues
        $ctx = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
            "http" => [
                "timeout" => 3
            ]
        ]);

        $dummyPng = base64_decode("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=");

        // Download category images
        $categoryImages = [
            'dress' => 'https://picsum.photos/400/300?random=10',
            'outer' => 'https://picsum.photos/400/300?random=20',
            'atasan' => 'https://picsum.photos/400/300?random=30',
            'bawahan' => 'https://picsum.photos/400/300?random=40',
            'aksesoris' => 'https://picsum.photos/400/300?random=50',
        ];

        foreach ($categoryImages as $name => $url) {
            try {
                $imageContent = file_get_contents($url, false, $ctx);
                if ($imageContent) {
                    Storage::disk('public')->put("categories/{$name}.jpg", $imageContent);
                } else {
                    Storage::disk('public')->put("categories/{$name}.jpg", $dummyPng);
                }
            } catch (\Exception $e) {
                Storage::disk('public')->put("categories/{$name}.jpg", $dummyPng);
            }
        }

        // 2. Admin User
        User::create([
            'name' => 'Admin Samawa',
            'email' => 'admin@samawa.com',
            'password' => Hash::make('password'),
            'phone' => '081122334455',
            'address' => 'Butik Samawa, Kemang Raya No. 45, Jakarta',
            'role' => 'admin',
        ]);

        // 3. Regular Customer User
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'user@samawa.com',
            'password' => Hash::make('password'),
            'phone' => '089988776655',
            'address' => 'Jl. Mawar No. 12, Kebayoran Baru, Jakarta Selatan',
            'role' => 'user',
        ]);

        // 4. Categories
        $cats = [
            ['name' => 'Dress', 'slug' => 'dress', 'image' => 'categories/dress.jpg', 'desc' => 'Koleksi gaun sutra, brokat, dan satin yang memancarkan pesona keanggunan wanita.'],
            ['name' => 'Outer', 'slug' => 'outer', 'image' => 'categories/outer.jpg', 'desc' => 'Blazer tweed, mantel wol, dan rajutan kasmir eksklusif untuk kehangatan berkelas.'],
            ['name' => 'Atasan', 'slug' => 'atasan', 'image' => 'categories/atasan.jpg', 'desc' => 'Blus sutra, kemeja satin premium, dan atasan renda elegan.'],
            ['name' => 'Bawahan', 'slug' => 'bawahan', 'image' => 'categories/bawahan.jpg', 'desc' => 'Celana panjang satin mengalir dan rok lipit sutra untuk penampilan formal modern.'],
            ['name' => 'Aksesoris', 'slug' => 'aksesoris', 'image' => 'categories/aksesoris.jpg', 'desc' => 'Syal sutra murni, sabuk mutiara, dan bros mewah sebagai pelengkap gaya Anda.']
        ];

        $categoryModels = [];
        foreach ($cats as $c) {
            $categoryModels[$c['name']] = Category::create([
                'name' => $c['name'],
                'slug' => $c['slug'],
                'description' => $c['desc'],
                'image' => $c['image'],
                'is_active' => true,
            ]);
        }

        // 5. Products
        $products = [
            // Dress
            ['name' => 'Audrey Silk Dress', 'cat' => 'Dress', 'price' => 1250000, 'stock' => 15, 'desc' => 'Gaun terbuat dari sutra Mulberry murni dengan detail drape elegan pada bagian leher.'],
            ['name' => 'Victoria Velvet Gown', 'cat' => 'Dress', 'price' => 2450000, 'stock' => 8, 'desc' => 'Gaun beludru mewah berpotongan a-line, sangat pas untuk pesta malam resmi.'],
            ['name' => 'Isabella Satin Dress', 'cat' => 'Dress', 'price' => 1350000, 'stock' => 20, 'desc' => 'Gaun satin premium dengan tali bahu tipis dan potongan punggung terbuka yang menawan.'],
            ['name' => 'Giselle Lace Maxi', 'cat' => 'Dress', 'price' => 1890000, 'stock' => 5, 'desc' => 'Gaun renda maksi bersiluet longgar dengan detail brokat bordir handmade.'],
            // Outer
            ['name' => 'Elizabeth Tweed Blazer', 'cat' => 'Outer', 'price' => 1550000, 'stock' => 12, 'desc' => 'Blazer tweed klasik bergaya Chanel dengan kancing mutiara emas.'],
            ['name' => 'Clara Cashmere Cardigan', 'cat' => 'Outer', 'price' => 1100000, 'stock' => 18, 'desc' => 'Kardigan rajut kasmir ultra lembut yang memberikan kenyamanan maksimal.'],
            ['name' => 'Helena Silk Kimono', 'cat' => 'Outer', 'price' => 950000, 'stock' => 10, 'desc' => 'Kimono sutra bercorak bunga klasik untuk pelengkap pakaian santai namun mewah.'],
            ['name' => 'Florence Wool Coat', 'cat' => 'Outer', 'price' => 3200000, 'stock' => 4, 'desc' => 'Mantel wol tebal bersiluet ramping dengan ikat pinggang senada.'],
            // Atasan
            ['name' => 'Amelia Silk Blouse', 'cat' => 'Atasan', 'price' => 850000, 'stock' => 25, 'desc' => 'Blus sutra berpotongan leher V dengan lengan lonceng yang anggun.'],
            ['name' => 'Diana Satin Shirt', 'cat' => 'Atasan', 'price' => 720000, 'stock' => 30, 'desc' => 'Kemeja satin mengkilap berkerah klasik, cocok untuk tampilan formal berkelas.'],
            ['name' => 'Evelyn Lace Top', 'cat' => 'Atasan', 'price' => 980000, 'stock' => 15, 'desc' => 'Atasan kerah tinggi berbahan renda premium dengan furing sutra.'],
            ['name' => 'Genevieve Ruffle Blouse', 'cat' => 'Atasan', 'price' => 790000, 'stock' => 20, 'desc' => 'Blus dengan detail kerutan (ruffles) manis di bagian dada dan pundak.'],
            // Bawahan
            ['name' => 'Ophelia Silk Trousers', 'cat' => 'Bawahan', 'price' => 1050000, 'stock' => 15, 'desc' => 'Celana kulot berbahan sutra tebal yang jatuh sempurna saat dikenakan.'],
            ['name' => 'Juliet Pleated Skirt', 'cat' => 'Bawahan', 'price' => 920000, 'stock' => 22, 'desc' => 'Rok lipit sutra midi dengan karet pinggang elastis berlapis benang emas.'],
            ['name' => 'Rosalind Satin Pants', 'cat' => 'Bawahan', 'price' => 890000, 'stock' => 14, 'desc' => 'Celana lurus satin premium dengan potongan pinggang tinggi (high waist).'],
            ['name' => 'Valeria Wide Leg Jeans', 'cat' => 'Bawahan', 'price' => 850000, 'stock' => 17, 'desc' => 'Jeans berpotongan lebar berkelas dengan sentuhan akhir washed indigo.'],
            // Aksesoris
            ['name' => 'Cassandra Pearl Belt', 'cat' => 'Aksesoris', 'price' => 450000, 'stock' => 25, 'desc' => 'Sabuk pinggang berhias mutiara air tawar asli dengan klip logam emas.'],
            ['name' => 'Guinevere Silk Scarf', 'cat' => 'Aksesoris', 'price' => 590000, 'stock' => 30, 'desc' => 'Syal sutra motif cetak tangan eksklusif berukuran 90x90 cm.'],
            ['name' => 'Aurelia Gold Brooch', 'cat' => 'Aksesoris', 'price' => 380000, 'stock' => 40, 'desc' => 'Bros berlapis emas 18k berbentuk logo Samawa bermotif ukiran bunga.'],
            ['name' => 'Luxury Velvet Hairband', 'cat' => 'Aksesoris', 'price' => 250000, 'stock' => 50, 'desc' => 'Bando rambut berbahan beludru hitam tebal bertabur manik-manik mutiara.']
        ];

        foreach ($products as $index => $p) {
            $prodNum = $index + 1;
            
            // Try downloading product main image
            try {
                $imageContent = file_get_contents("https://picsum.photos/400/500?random=" . $prodNum, false, $ctx);
                if ($imageContent) {
                    Storage::disk('public')->put("products/product-{$prodNum}.jpg", $imageContent);
                } else {
                    Storage::disk('public')->put("products/product-{$prodNum}.jpg", $dummyPng);
                }
            } catch (\Exception $e) {
                Storage::disk('public')->put("products/product-{$prodNum}.jpg", $dummyPng);
            }

            // Create product model instance
            $productModel = Product::create([
                'category_id' => $categoryModels[$p['cat']]->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['desc'],
                'price' => $p['price'],
                'discount' => $prodNum % 4 === 0 ? 15 : 0, // 15% discount on every 4th product
                'stock' => $prodNum % 6 === 0 ? 0 : $p['stock'], // Some products are out of stock
                'weight' => 500,
                'sizes' => json_encode(['S', 'M', 'L', 'XL']),
                'colors' => json_encode(['Black', 'Gold', 'Cream', 'Emerald']),
                'image' => "products/product-{$prodNum}.jpg",
                'is_active' => true,
                'is_featured' => $prodNum <= 4,
                'is_new_arrival' => $prodNum > 15,
                'is_best_seller' => ($prodNum > 4 && $prodNum <= 8),
            ]);

            // Seed 3 extra product gallery images
            for ($g = 1; $g <= 3; $g++) {
                $galImgName = "products/product-{$prodNum}-gallery-{$g}.jpg";
                try {
                    $galContent = file_get_contents("https://picsum.photos/400/500?random=" . ($prodNum * 10 + $g), false, $ctx);
                    if ($galContent) {
                        Storage::disk('public')->put($galImgName, $galContent);
                    } else {
                        Storage::disk('public')->put($galImgName, $dummyPng);
                    }
                } catch (\Exception $e) {
                    Storage::disk('public')->put($galImgName, $dummyPng);
                }

                ProductImage::create([
                    'product_id' => $productModel->id,
                    'image' => $galImgName
                ]);
            }
        }

        // 6. Testimonials
        Testimonial::create([
            'user_id' => 2,
            'name' => 'Novi Yanti',
            'role' => 'Sosialita & Kolektor Fashion',
            'rating' => 5,
            'message' => 'Audrey Silk Dress yang saya beli sangat menakjubkan. Bahannya sangat lembut, jahitannya rapi, dan semua mata tertuju pada saya saat menghadiri gala dinner minggu lalu.',
            'avatar' => null,
            'is_approved' => true,
        ]);

        Testimonial::create([
            'user_id' => 2,
            'name' => 'Rania Alexandra',
            'role' => 'Pengusaha Muda',
            'rating' => 5,
            'message' => 'Layanan butik Samawa luar biasa cepat. Blazer Elizabeth Tweed pas sekali di badan saya. Sangat berkelas dan menunjang penampilan saya dalam meeting penting.',
            'avatar' => null,
            'is_approved' => true,
        ]);

        Testimonial::create([
            'user_id' => 2,
            'name' => 'Pratiwi Kartika',
            'role' => 'Loyal Customer',
            'rating' => 4,
            'message' => 'Koleksi busananya selalu up to date. Pilihan kemeja satinnya sangat cocok untuk daily work wear yang formal namun tetap terasa premium dan elegan.',
            'avatar' => null,
            'is_approved' => true,
        ]);
    }
}
