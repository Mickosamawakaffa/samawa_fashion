# 🔒 SAMAWA Fashion - Security & Testing Checklist

Use this checklist to verify the stability, performance, and security of the SAMAWA Fashion e-commerce platform before deploying to production.

---

## 1. Audit Keamanan Dasar Laravel
- [ ] **.env Git Protection**: Pastikan `.env` tidak ter-commit ke Git. Cek berkas `.gitignore` sudah mencakup `.env` dan file `.env.*` lainnya.
- [ ] **Production Env Configuration**:
  - `APP_DEBUG` diset ke `false` di server production.
  - `APP_ENV` diset ke `production`.
- [ ] **Generate APP_KEY**: Jalankan `php artisan key:generate` khusus untuk production.
- [ ] **Akses Admin Route**: Verifikasi semua route admin dilindungi middleware `auth` dan `admin` (IsAdmin).
- [ ] **CSRF Protection**: Form html menyertakan `@csrf` token, dan form AJAX menyertakan token CSRF melalui header `X-CSRF-TOKEN`.
- [ ] **Mass Assignment Check**: Model `User` dan model lainnya hanya memperbolehkan mass assignment kolom non-sensitif (kolom `role` dan `is_active` diaudit dari fillable).

---

## 2. Validasi Input & Sanitasi
- [ ] **Form Requests & Validation**: Semua input form (Login, Register, Checkout, Produk, Ulasan) memiliki validasi yang ketat (`required`, `email`, `max`, `numeric`, `exists` dll).
- [ ] **Validasi File Upload**:
  - Validasi tipe file: `image|mimes:jpg,jpeg,png|max:2048`
  - Nama file dibuat acak/unik menggunakan `Str::random(20)`
  - File disimpan di `Storage::disk('public')` (di luar folder public langsung).
- [ ] **SQL Injection Prevention**: Kueri database menggunakan Eloquent/Query Builder dengan parameter binding. Tidak ada kueri raw dengan variabel masukan langsung.
- [ ] **XSS Prevention (Output Escaping)**: Semua output data masukan user menggunakan syntax Blade `{{ }}` (HTML escaped). Penggunaan `{!! !!}` sangat dibatasi dan diaudit keamanannya.

---

## 3. Rate Limiting & Protection
- [ ] **Route Throttling**:
  - POST `/login` dibatasi dengan middleware `throttle:5,1` (maksimal 5 kali percobaan per menit).
  - POST `/register` dibatasi dengan middleware `throttle:3,1` (maksimal 3 kali percobaan per menit).
  - POST `/checkout` dibatasi dengan middleware `throttle:10,1` (maksimal 10 kali percobaan per menit).
- [ ] **Google reCAPTCHA v3**:
  - Script reCAPTCHA dimuat di form Login, Register, dan Kontak.
  - Validasi token reCAPTCHA diproses di server.
- [ ] **Lockout Percobaan Login**: Akun otomatis dikunci sementara selama 1 menit jika terdeteksi 5 kali gagal login.

---

## 4. Keamanan Transaksi & Data Sensitif
- [ ] **Enkripsi Data Sensitif**: Informasi nomor rekening atau data sensitif pembayaran tidak disimpan dalam teks polos (plain text).
- [ ] **Activity Logs**: Semua aktivitas penting tercatat pada tabel `activity_logs` (kolom `user_id`, `action`, `description`, `ip_address`, `timestamps`).
- [ ] **User Data Policy (URL Manipulation)**:
  - User tidak bisa melihat order milik user lain meskipun mengganti parameter ID atau kode order di URL `/orders/{order_code}`.
  - Aturan policy diterapkan untuk Profil, Wishlist, Keranjang, dan Alamat.

---

## 5. HTTPS & Header Keamanan
- [ ] **Force HTTPS Scheme**: Redirect skema URL secara otomatis ke HTTPS pada server production via `URL::forceScheme('https')`.
- [ ] **Security Headers**: Middleware global menyertakan header keamanan berikut:
  - `X-Frame-Options: DENY`
  - `X-Content-Type-Options: nosniff`
  - `X-XSS-Protection: 1; mode=block`
  - `Referrer-Policy: strict-origin-when-cross-origin`

---

## 6. Backup & Disaster Recovery
- [ ] **Setup Harian Otomatis**: Konfigurasi `spatie/laravel-backup` berjalan setiap hari.
- [ ] **Cloud Storage Backup**: Backup terkirim ke penyimpanan eksternal (Google Drive / AWS S3).
- [ ] **Uji Coba Restore**: Proses pemulihan database dari backup diuji setidaknya 1 kali sebelum go-live.

---

## 7. Testing Fungsional

### 7.1 Flow Customer
- [ ] **Register, Login, Logout**: Registrasi user baru, login, dan logout berjalan tanpa hambatan.
- [ ] **Browse Produk, Filter, Search**: Mencari produk, memfilter kategori/harga, serta pencarian berjalan benar.
- [ ] **Tambah ke Cart, Ubah Qty, Hapus Item**: Menambah ke keranjang belanja, mengubah jumlah kuantiti, dan menghapus item dari keranjang dengan kalkulasi harga yang benar.
- [ ] **Checkout Lengkap**: Menyelesaikan pengisian data checkout hingga submit order sukses.
- [ ] **Pilih SEMUA Opsi Kurir**: Memilih kurir (JNE, POS, TIKI) secara bergantian pada form checkout, pastikan ongkir diperbarui dinamis dan tersimpan dengan benar saat disubmit.
- [ ] **Cek Riwayat Order & Detail Order**: Membuka riwayat order dan halaman detail pesanan, memastikan status progress bar dan data ongkos kirim serta total pembayaran sinkron.

### 7.2 Flow Admin
- [ ] **Tambah/Edit/Hapus Produk**: Mengelola produk (termasuk dummy & non-dummy) dan upload foto dengan kompresi berjalan aman.
- [ ] **Update Status Order**: Mengubah status pesanan melalui semua tahapan transisi status (`pending` -> `processing` -> `shipped` -> `delivered`).
- [ ] **Laporan Penjualan**: Laporan penjualan bulanan menampilkan data pendapatan dan total order yang sesuai dengan status pesanan lunas/delivered.
- [ ] **Bulk Action & Quick Update**: Menguji ubah status massal & dropdown di tabel `/admin/orders` berjalan lancar, dan memblokir perubahan status "Dikirim" tanpa nomor resi (redirect ke detail).

---

## 8. Skenario Uji Edge Case (Nakal)
- [ ] **Beli Stok Kosong**: Checkout produk dengan stok 0 harus ditolak.
- [ ] **Race Condition Stok Terakhir**: Skenario 2 user checkout produk dengan sisa stok terakhir bersamaan ditangani aman menggunakan `lockForUpdate()`.
- [ ] **Akses Admin Tanpa Login**: Membuka `/admin/*` diarahkan kembali ke halaman login.
- [ ] **Akses Admin User Biasa**: Pengguna biasa diblokir dengan halaman `403 Forbidden` saat mencoba mengakses `/admin/*`.
- [ ] **Form Kosong / Invalid**: Form mengembalikan pesan error validasi yang jelas dan tidak menyebabkan server crash/500.
- [ ] **Upload File Jahat**: Upload berkas non-gambar (seperti `.php` atau `.exe`) ditolak oleh validator file upload.
- [ ] **Input Negatif**: Memasukkan harga atau quantity negatif ditolak oleh form.
- [ ] **URL Manipulation (Order Lain)**: Mengakses URL pesanan user lain diblokir dengan respon `403 Forbidden`.

---

## 9. Performa & Responsif
- [ ] **N+1 Query & Eager Loading**: Halaman dengan 100+ produk ter-load cepat tanpa N+1 queries. Eager loading `with('category')` dan aggregasi terimplementasi dengan baik.
- [ ] **Kompresi Gambar**: Semua gambar produk terkompresi otomatis menjadi maks 800px lebar dengan kualitas 80%.
- [ ] **Koneksi Lambat**: Website diuji responsif dan dapat digunakan pada simulasi koneksi 3G lambat.
- [ ] **Responsive Design**: Semua tombol, kolom input, dan menu navigasi terlihat rapi dan fungsional di HP, tablet, maupun layar desktop.
