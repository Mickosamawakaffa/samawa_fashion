@extends('layouts.frontend')

@section('title', 'Syarat & Ketentuan - Samawa Fashion')

@section('content')
<div class="py-5" style="background-color: #FAF6F0;">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2 style="font-family: 'Playfair Display', serif; font-weight: bold;">Syarat & Ketentuan</h2>
            <div class="divider mx-auto" style="width: 80px; height: 3px; background-color: var(--gold-color);"></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 15px; background-color: #ffffff;">
                    <div class="legal-content text-muted" style="line-height: 1.8;">
                        <p class="mb-4">Selamat datang di <strong>Samawa Fashion</strong>. Syarat & ketentuan berikut ini mengatur penggunaan layanan dan pembelian produk di toko online kami. Dengan mengakses situs web kami dan melakukan pemesanan, Anda dianggap telah membaca, memahami, dan menyetujui seluruh ketentuan ini.</p>
                        
                        <h5 class="fw-bold text-dark mt-4 mb-3" style="font-family: 'Playfair Display', serif;"><i class="fas fa-shopping-bag text-gold me-2"></i>1. Ketentuan Pemesanan</h5>
                        <ul class="ps-3 mb-4">
                            <li>Pelanggan wajib memberikan informasi data diri yang akurat, lengkap, dan terbaru saat melakukan pendaftaran akun maupun checkout.</li>
                            <li>Pemesanan produk tunduk pada ketersediaan stok produk. Kami berhak membatalkan pesanan apabila stok barang habis.</li>
                            <li>Pesanan yang telah dibuat akan diproses setelah pembayaran diverifikasi oleh sistem kami.</li>
                        </ul>

                        <h5 class="fw-bold text-dark mt-4 mb-3" style="font-family: 'Playfair Display', serif;"><i class="fas fa-wallet text-gold me-2"></i>2. Pembayaran</h5>
                        <ul class="ps-3 mb-4">
                            <li>Pembayaran dapat dilakukan melalui metode transfer bank manual, pembayaran online otomatis (Midtrans), atau Cash on Delivery (COD) sesuai pilihan saat checkout.</li>
                            <li>Untuk transfer bank manual, pelanggan harus mengunggah bukti pembayaran yang sah maksimal dalam 24 jam. Jika lewat, pesanan akan dibatalkan otomatis.</li>
                            <li>Seluruh biaya transaksi e-commerce dan biaya administrasi pembayaran bank ditanggung oleh pelanggan, kecuali ada promo tertentu.</li>
                        </ul>

                        <h5 class="fw-bold text-dark mt-4 mb-3" style="font-family: 'Playfair Display', serif;"><i class="fas fa-truck text-gold me-2"></i>3. Pengiriman & Ekspedisi</h5>
                        <ul class="ps-3 mb-4">
                            <li>Pengiriman pesanan dilakukan menggunakan kurir mitra resmi kami (JNE, J&T, SiCepat).</li>
                            <li>Biaya pengiriman dihitung secara otomatis berdasarkan berat produk total dan alamat tujuan pengiriman menggunakan integrasi API RajaOngkir.</li>
                            <li>Keterlambatan atau kerusakan produk akibat kesalahan pihak ekspedisi di luar tanggung jawab Samawa Fashion, namun kami akan membantu proses klaim ke kurir terkait.</li>
                        </ul>

                        <h5 class="fw-bold text-dark mt-4 mb-3" style="font-family: 'Playfair Display', serif;"><i class="fas fa-exclamation-triangle text-gold me-2"></i>4. Force Majeure</h5>
                        <p class="mb-4">Kami dibebaskan dari tanggung jawab atas keterlambatan atau kegagalan pemenuhan kewajiban pengiriman barang akibat peristiwa di luar kendali wajar kami (Force Majeure), termasuk namun tidak terbatas pada bencana alam, kebakaran, pemogokan massal, perang, kerusuhan, pandemi, keputusan pemerintah, maupun gangguan jalur logistik nasional.</p>

                        <h5 class="fw-bold text-dark mt-4 mb-3" style="font-family: 'Playfair Display', serif;"><i class="fas fa-balance-scale text-gold me-2"></i>5. Hak & Kewajiban</h5>
                        <ul class="ps-3 mb-4">
                            <li>Kami berkomitmen menjaga kualitas produk busana muslimah terbaik dan memberikan pelayanan terbaik bagi pelanggan.</li>
                            <li>Pelanggan berkewajiban melakukan pembayaran yang sah, menjaga kerahasiaan akun login, dan tidak menyalahgunakan platform kami untuk tindakan melanggar hukum.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
