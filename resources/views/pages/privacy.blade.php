@extends('layouts.frontend')

@section('title', 'Kebijakan Privasi - Samawa Fashion')

@section('content')
<div class="py-5" style="background-color: #FAF6F0;">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2 style="font-family: 'Playfair Display', serif; font-weight: bold;">Kebijakan Privasi</h2>
            <div class="divider mx-auto" style="width: 80px; height: 3px; background-color: var(--gold-color);"></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 15px; background-color: #ffffff;">
                    <div class="legal-content text-muted" style="line-height: 1.8;">
                        <p class="mb-4">Kebijakan Privasi ini menjelaskan bagaimana <strong>Samawa Fashion</strong> mengumpulkan, menggunakan, menyimpan, dan melindungi informasi pribadi Anda ketika Anda mengunjungi website kami dan membeli produk busana muslimah kami. Keamanan data Anda adalah prioritas utama kami.</p>
                        
                        <h5 class="fw-bold text-dark mt-4 mb-3" style="font-family: 'Playfair Display', serif;"><i class="fas fa-database text-gold me-2"></i>1. Informasi yang Kami Kumpulkan</h5>
                        <ul class="ps-3 mb-4">
                            <li><strong>Informasi Akun & Kontak:</strong> Nama lengkap, alamat email, nomor telepon, dan password saat Anda membuat akun.</li>
                            <li><strong>Informasi Pengiriman:</strong> Alamat pengiriman lengkap, kecamatan, kota, provinsi, dan kode pos untuk mengirimkan pesanan Anda.</li>
                            <li><strong>Informasi Transaksi:</strong> Riwayat pembelian produk, metode pembayaran, nominal tagihan, dan bukti transfer pembayaran.</li>
                        </ul>

                        <h5 class="fw-bold text-dark mt-4 mb-3" style="font-family: 'Playfair Display', serif;"><i class="fas fa-cogs text-gold me-2"></i>2. Penggunaan Informasi Data Anda</h5>
                        <p class="mb-2">Kami menggunakan data pribadi Anda untuk kepentingan sebagai berikut:</p>
                        <ul class="ps-3 mb-4">
                            <li>Memproses transaksi pemesanan produk dan pengiriman paket oleh pihak logistik ekspedisi.</li>
                            <li>Mengirimkan email konfirmasi pesanan, pembaruan status pengiriman resi, dan notifikasi transaksi lainnya.</li>
                            <li>Menghubungi Anda perihal kendala transaksi belanja atau verifikasi pembayaran.</li>
                            <li>Menganalisis performa website belanja kami demi meningkatkan kualitas pelayanan toko online.</li>
                        </ul>

                        <h5 class="fw-bold text-dark mt-4 mb-3" style="font-family: 'Playfair Display', serif;"><i class="fas fa-shield-alt text-gold me-2"></i>3. Perlindungan Informasi Pribadi</h5>
                        <p class="mb-4">Kami menerapkan standar keamanan enkripsi data (termasuk transfer HTTPS / SSL) untuk melindungi informasi sensitif Anda. Akses database dilindungi kata sandi yang ketat, dan kami tidak pernah menjual atau menyewakan informasi pribadi pelanggan kepada pihak ketiga manapun untuk tujuan pemasaran komersial.</p>

                        <h5 class="fw-bold text-dark mt-4 mb-3" style="font-family: 'Playfair Display', serif;"><i class="fas fa-user-check text-gold me-2"></i>4. Hak Pengguna atas Data</h5>
                        <ul class="ps-3 mb-4">
                            <li>Anda memiliki hak untuk memperbarui informasi data profil, nomor HP, dan alamat pengiriman Anda kapan saja di menu edit profil akun.</li>
                            <li>Anda berhak meminta penghapusan akun beserta informasi riwayat belanja Anda dengan menghubungi tim customer support kami.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
