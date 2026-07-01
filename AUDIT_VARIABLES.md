# Audit Variabel View — SAMAWA Fashion

Dokumentasi audit variabel ini mencatat seluruh variabel penting yang dikonsumsi oleh view yang memiliki blok `@php` dan mencocokkannya dengan variabel yang dikirimkan oleh Controller masing-masing.

---

## 1. Halaman Produk & Review

### 1.1 `resources/views/products/_grid.blade.php`
* **Controller Pengirim**: `App\Http\Controllers\ProductController@index`
* **Variabel yang Diterima**:
  * `$products` (LengthAwarePaginator) — Koleksi produk yang aktif.
* **Variabel Lokal / Loop**:
  * `$product` — Elemen tunggal dari `$products` dalam `@foreach`.
  * `$primaryImg`, `$isExternal`, `$imgSrc` — Variabel lokal untuk pemrosesan path gambar.

### 1.2 `resources/views/products/show.blade.php`
* **Controller Pengirim**: `App\Http\Controllers\ProductController@show`
* **Variabel yang Diterima**:
  * `$product` (Product) — Instance data produk yang akan ditampilkan.
  * `$relatedProducts` (Collection) — Koleksi produk terkait dalam kategori yang sama.
  * `$canReview` (Boolean) — Status apakah user login diizinkan mengulas produk ini.
  * `$reviews` (LengthAwarePaginator) — Koleksi ulasan produk ini.

### 1.3 `resources/views/admin/products/show.blade.php`
* **Controller Pengirim**: `App\Http\Controllers\Admin\ProductController@show`
* **Variabel yang Diterima**:
  * `$product` (Product) — Instance data detail produk.
* **Variabel Lokal / Loop**:
  * `$showImg`, `$showIsExt`, `$showUrl` — Variabel lokal gambar utama.
  * `$image` — Iterasi galeri foto produk.
  * `$gIsExt`, `$gUrl` — Variabel lokal galeri foto produk.

### 1.4 `resources/views/admin/products/edit.blade.php`
* **Controller Pengirim**: `App\Http\Controllers\Admin\ProductController@edit`
* **Variabel yang Diterima**:
  * `$product` (Product) — Instance data produk yang di-edit.
  * `$categories` (Collection) — Daftar semua kategori aktif.

---

## 2. Halaman Cart & Checkout

### 2.1 `resources/views/cart/index.blade.php`
* **Controller Pengirim**: `App\Http\Controllers\CartController@index`
* **Variabel yang Diterima**:
  * `$cart` (Object) — Menyimpan items (Collection) dan total (Numeric).

### 2.2 `resources/views/checkout/index.blade.php`
* **Controller Pengirim**: `App\Http\Controllers\CheckoutController@index`
* **Variabel yang Diterima**:
  * `$cartItems` (Collection) — Koleksi item dalam keranjang belanja.
  * `$cartTotal` (Numeric) — Total harga sebelum biaya kirim/diskon.
  * `$freeShippingThreshold` (Numeric) — Batas minimal belanja untuk gratis ongkir.
  * `$provinces` (Collection) — Daftar provinsi untuk form alamat.
  * `$user` (User) — Pengguna yang sedang login.
  * `$defaultAddress` (ShippingAddress/Null) — Alamat default pengguna.

### 2.3 `resources/views/checkout/success.blade.php`
* **Controller Pengirim**: `App\Http\Controllers\CheckoutController@success` (atau redirect dari payment callback)
* **Variabel yang Diterima**:
  * `$order` (Order) — Instance order yang baru berhasil dibuat.

---

## 3. Halaman Order & Invoice

### 3.1 `resources/views/orders/show.blade.php`
* **Controller Pengirim**: `App\Http\Controllers\OrderController@show`
* **Variabel yang Diterima**:
  * `$order` (Order) — Detail transaksi order milik user.

### 3.2 `resources/views/admin/orders/invoice.blade.php`
* **Controller Pengirim**: `App\Http\Controllers\Admin\OrderController@printInvoice`
* **Variabel yang Diterima**:
  * `$order` (Order) — Detail transaksi order untuk cetak PDF.

### 3.3 `resources/views/emails/order_status_updated.blade.php`
* **Mailable Pengirim**: `App\Mail\OrderStatusUpdatedMail`
* **Variabel yang Diterima**:
  * `$order` (Order) — Detail transaksi order yang statusnya berubah.

---

## 4. Layouts & General Views

### 4.1 `resources/views/layouts/frontend.blade.php`
* **Global / Auth**:
  * Menggunakan data login `auth()->user()`.
  * `$wishCount` — Dihitung dinamis jika user terautentikasi.
  * `$cartCount` — Dihitung dinamis jika user terautentikasi.

### 4.2 `resources/views/home.blade.php`
* **Controller Pengirim**: `App\Http\Controllers\HomeController@index`
* **Variabel yang Diterima**:
  * `$banners` (Collection) — Daftar banner promosi aktif.
  * `$categories` (Collection) — Daftar kategori produk.
  * `$newProducts` (Collection) — Koleksi produk terbaru.
  * `$bestSellers` (Collection) — Koleksi produk terlaris.
  * `$testimonials` (Collection) — Testimoni terpopuler yang disetujui.
