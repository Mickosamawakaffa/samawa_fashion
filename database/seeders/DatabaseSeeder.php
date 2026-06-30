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
        // Seed local provinces and cities
        $this->call(LocationSeeder::class);

        // 1. Setup Stream Context to avoid Windows/Laragon SSL issues
        $ctx = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
            "http" => [
                "timeout" => 10
            ]
        ]);

        $dummyPng = base64_decode("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=");

        // Download category images (using fashion-specific high quality unsplash URLs)
        $categoryImages = [
            'dress' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=400&h=300&q=80',
            'outer' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=400&h=300&q=80',
            'atasan' => 'https://images.unsplash.com/photo-1603252109303-2751441dd157?auto=format&fit=crop&w=400&h=300&q=80',
            'bawahan' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=400&h=300&q=80',
            'aksesoris' => 'https://images.unsplash.com/photo-1583496929240-ab97d515881a?auto=format&fit=crop&w=400&h=300&q=80',
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

        // 1b. Download specific clothing dummy images for mapping
        $fashionDummyUrls = [
            'kemeja-putih-1.jpg' => 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?auto=format&fit=crop&w=600&h=800&q=80',
            'kemeja-putih-2.jpg' => 'https://images.unsplash.com/photo-1603252109303-2751441dd157?auto=format&fit=crop&w=600&h=800&q=80',
            'blouse-krem-1.jpg' => 'https://images.unsplash.com/photo-1584030373081-f37b7bb4fa8e?auto=format&fit=crop&w=600&h=800&q=80',
            'blouse-krem-2.jpg' => 'https://images.unsplash.com/photo-1548624149-f9b1859aa7d0?auto=format&fit=crop&w=600&h=800&q=80',
            'dress-hitam-1.jpg' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=600&h=800&q=80',
            'dress-hitam-2.jpg' => 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=600&h=800&q=80',
            'blazer-coklat-1.jpg' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=600&h=800&q=80',
            'blazer-coklat-2.jpg' => 'https://images.unsplash.com/photo-1548624313-0396c75e4b1a?auto=format&fit=crop&w=600&h=800&q=80',
            'kaos-polos-1.jpg' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=600&h=800&q=80',
            'celana-kulot-1.jpg' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=600&h=800&q=80',
            'celana-palazzo-1.jpg' => 'https://images.unsplash.com/photo-1509551388413-e18d0ac5d495?auto=format&fit=crop&w=600&h=800&q=80',
            'celana-formal-1.jpg' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?auto=format&fit=crop&w=600&h=800&q=80',
            'celana-jeans-1.jpg' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=600&h=800&q=80',
            'rok-midi-1.jpg' => 'https://images.unsplash.com/photo-1583496929240-ab97d515881a?auto=format&fit=crop&w=600&h=800&q=80',
            'default-fashion.jpg' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=600&h=800&q=80',
        ];

        foreach ($fashionDummyUrls as $filename => $url) {
            $destPath = "products/dummy/{$filename}";
            try {
                $imageContent = file_get_contents($url, false, $ctx);
                if ($imageContent) {
                    Storage::disk('public')->put($destPath, $imageContent);
                } else {
                    Storage::disk('public')->put($destPath, $dummyPng);
                }
            } catch (\Exception $e) {
                Storage::disk('public')->put($destPath, $dummyPng);
            }
        }

        // 2. Admin User
        User::create([
            'name' => 'Admin Samawa',
            'email' => 'admin@samawa.com',
            'password' => Hash::make('password'),
            'phone' => '087853391433',
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

        // 5. Products (Seeded with pre-defined categories mapping to relevant clothing images)
        $products = [
            // Dress
            ['name' => 'Audrey Silk Dress', 'cat' => 'Dress', 'price' => 1250000, 'stock' => 15, 'desc' => 'Gaun terbuat dari sutra Mulberry murni dengan detail drape elegan pada bagian leher.', 'images' => ['products/dummy/dress-hitam-1.jpg', 'products/dummy/dress-hitam-2.jpg']],
            ['name' => 'Victoria Velvet Gown', 'cat' => 'Dress', 'price' => 2450000, 'stock' => 8, 'desc' => 'Gaun beludru mewah berpotongan a-line, sangat pas untuk pesta malam resmi.', 'images' => ['products/dummy/dress-hitam-2.jpg', 'products/dummy/dress-hitam-1.jpg']],
            ['name' => 'Isabella Satin Dress', 'cat' => 'Dress', 'price' => 1350000, 'stock' => 20, 'desc' => 'Gaun satin premium dengan tali bahu tipis dan potongan punggung terbuka yang menawan.', 'images' => ['products/dummy/dress-hitam-1.jpg', 'products/dummy/dress-hitam-2.jpg']],
            ['name' => 'Giselle Lace Maxi', 'cat' => 'Dress', 'price' => 1890000, 'stock' => 5, 'desc' => 'Gaun renda maksi bersiluet longgar dengan detail brokat bordir handmade.', 'images' => ['products/dummy/dress-hitam-2.jpg', 'products/dummy/dress-hitam-1.jpg']],
            // Outer
            ['name' => 'Elizabeth Tweed Blazer', 'cat' => 'Outer', 'price' => 1550000, 'stock' => 12, 'desc' => 'Blazer tweed klasik bergaya Chanel dengan kancing mutiara emas.', 'images' => ['products/dummy/blazer-coklat-1.jpg', 'products/dummy/blazer-coklat-2.jpg']],
            ['name' => 'Clara Cashmere Cardigan', 'cat' => 'Outer', 'price' => 1100000, 'stock' => 18, 'desc' => 'Kardigan rajut kasmir ultra lembut yang memberikan kenyamanan maksimal.', 'images' => ['products/dummy/blazer-coklat-2.jpg', 'products/dummy/blazer-coklat-1.jpg']],
            ['name' => 'Helena Silk Kimono', 'cat' => 'Outer', 'price' => 950000, 'stock' => 10, 'desc' => 'Kimono sutra bercorak bunga klasik untuk pelengkap pakaian santai namun mewah.', 'images' => ['products/dummy/default-fashion.jpg']],
            ['name' => 'Florence Wool Coat', 'cat' => 'Outer', 'price' => 3200000, 'stock' => 4, 'desc' => 'Mantel wol tebal bersiluet ramping dengan ikat pinggang senada.', 'images' => ['products/dummy/blazer-coklat-1.jpg', 'products/dummy/blazer-coklat-2.jpg']],
            // Atasan
            ['name' => 'Amelia Silk Blouse', 'cat' => 'Atasan', 'price' => 850000, 'stock' => 25, 'desc' => 'Blus sutra berpotongan leher V dengan lengan lonceng yang anggun.', 'images' => ['products/dummy/blouse-krem-1.jpg', 'products/dummy/blouse-krem-2.jpg']],
            ['name' => 'Diana Satin Shirt', 'cat' => 'Atasan', 'price' => 720000, 'stock' => 30, 'desc' => 'Kemeja satin mengkilap berkerah klasik, cocok untuk tampilan formal berkelas.', 'images' => ['products/dummy/kemeja-putih-1.jpg', 'products/dummy/kemeja-putih-2.jpg']],
            ['name' => 'Evelyn Lace Top', 'cat' => 'Atasan', 'price' => 980000, 'stock' => 15, 'desc' => 'Atasan kerah tinggi berbahan renda premium dengan furing sutra.', 'images' => ['products/dummy/blouse-krem-2.jpg', 'products/dummy/blouse-krem-1.jpg']],
            ['name' => 'Genevieve Ruffle Blouse', 'cat' => 'Atasan', 'price' => 790000, 'stock' => 20, 'desc' => 'Blus dengan detail kerutan (ruffles) manis di bagian dada dan pundak.', 'images' => ['products/dummy/blouse-krem-1.jpg', 'products/dummy/blouse-krem-2.jpg']],
            // Bawahan
            ['name' => 'Ophelia Silk Trousers', 'cat' => 'Bawahan', 'price' => 1050000, 'stock' => 15, 'desc' => 'Celana kulot berbahan sutra tebal yang jatuh sempurna saat dikenakan.', 'images' => ['products/dummy/celana-kulot-1.jpg', 'products/dummy/celana-palazzo-1.jpg']],
            ['name' => 'Juliet Pleated Skirt', 'cat' => 'Bawahan', 'price' => 920000, 'stock' => 22, 'desc' => 'Rok lipit sutra midi dengan karet pinggang elastis berlapis benang emas.', 'images' => ['products/dummy/rok-midi-1.jpg']],
            ['name' => 'Rosalind Satin Pants', 'cat' => 'Bawahan', 'price' => 890000, 'stock' => 14, 'desc' => 'Celana lurus satin premium dengan potongan pinggang tinggi (high waist).', 'images' => ['products/dummy/celana-formal-1.jpg', 'products/dummy/celana-palazzo-1.jpg']],
            ['name' => 'Valeria Wide Leg Jeans', 'cat' => 'Bawahan', 'price' => 850000, 'stock' => 17, 'desc' => 'Jeans berpotongan lebar berkelas dengan sentuhan akhir washed indigo.', 'images' => ['products/dummy/celana-jeans-1.jpg']],
            // Aksesoris
            ['name' => 'Cassandra Pearl Belt', 'cat' => 'Aksesoris', 'price' => 450000, 'stock' => 25, 'desc' => 'Sabuk pinggang berhias mutiara air tawar asli dengan klip logam emas.', 'images' => ['products/dummy/default-fashion.jpg']],
            ['name' => 'Guinevere Silk Scarf', 'cat' => 'Aksesoris', 'price' => 590000, 'stock' => 30, 'desc' => 'Syal sutra motif cetak tangan eksklusif berukuran 90x90 cm.', 'images' => ['products/dummy/default-fashion.jpg']],
            ['name' => 'Aurelia Gold Brooch', 'cat' => 'Aksesoris', 'price' => 380000, 'stock' => 40, 'desc' => 'Bros berlapis emas 18k berbentuk logo Samawa bermotif ukiran bunga.', 'images' => ['products/dummy/default-fashion.jpg']],
            ['name' => 'Luxury Velvet Hairband', 'cat' => 'Aksesoris', 'price' => 250000, 'stock' => 50, 'desc' => 'Bando rambut berbahan beludru hitam tebal bertabur manik-manik mutiara.', 'images' => ['products/dummy/default-fashion.jpg']]
        ];

        foreach ($products as $index => $p) {
            $prodNum = $index + 1;
            
            $images = $p['images'] ?? ['products/dummy/default-fashion.jpg'];
            $mainImage = $images[0];

            // Create product model instance
            $productModel = Product::create([
                'category_id' => $categoryModels[$p['cat']]->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['desc'],
                'price' => $p['price'],
                'discount' => $prodNum % 4 === 0 ? 15 : 0, 
                'stock' => $prodNum % 6 === 0 ? 0 : $p['stock'], 
                'weight' => 500,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Black', 'Gold', 'Cream', 'Emerald'],
                'image' => $mainImage,
                'is_active' => true,
                'is_featured' => $prodNum <= 4,
                'is_new_arrival' => $prodNum > 15,
                'is_best_seller' => ($prodNum > 4 && $prodNum <= 8),
                'is_dummy' => true,
            ]);

            // Seed ProductImages
            foreach ($images as $gIdx => $imgPath) {
                ProductImage::create([
                    'product_id' => $productModel->id,
                    'image_path' => $imgPath,
                    'is_primary' => ($gIdx === 0),
                    'sort_order' => $gIdx,
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

        $this->call(ProductSeeder::class);
    }
}
