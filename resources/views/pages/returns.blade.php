@extends('layouts.frontend')

@section('title', 'Kebijakan Retur & Refund - Samawa Fashion')

@section('content')
<div class="py-5" style="background-color: #FAF6F0;">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2 style="font-family: 'Playfair Display', serif; font-weight: bold;">Kebijakan Retur & Refund</h2>
            <div class="divider mx-auto" style="width: 80px; height: 3px; background-color: var(--gold-color);"></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 15px; background-color: #ffffff;">
                    <div class="legal-content text-muted" style="line-height: 1.8;">
                        <p class="mb-4">Di <strong>Samawa Fashion</strong>, kepuasan Anda adalah segalanya bagi kami. Jika produk busana muslimah yang Anda terima tidak sesuai harapan, mengalami cacat produksi, atau salah ukuran, kami menyediakan kebijakan pengembalian barang (retur) dan pengembalian uang (refund) dengan ketentuan mudah berikut:</p>
                        
                        <h5 class="fw-bold text-dark mt-4 mb-3" style="font-family: 'Playfair Display', serif;"><i class="fas fa-clipboard-list text-gold me-2"></i>1. Syarat Pengembalian Barang (Retur)</h5>
                        <p class="mb-2">Produk yang diajukan untuk retur harus memenuhi syarat di bawah ini:</p>
                        <ul class="ps-3 mb-4">
                            <li>Produk belum pernah dipakai, belum dicuci, dan tidak berbau (parfum, detergen, dll).</li>
                            <li>Tag label harga produk masih terpasang utuh dan kemasan produk tidak rusak.</li>
                            <li>Mengajukan klaim retur maksimal dalam waktu <strong>7 hari</strong> sejak barang diterima (berdasarkan data tracking kurir ekspedisi).</li>
                            <li>Wajib melampirkan video unboxing paket saat dibuka sebagai bukti valid jika terjadi cacat produk atau kesalahan kirim jenis produk oleh tim kami.</li>
                        </ul>

                        <h5 class="fw-bold text-dark mt-4 mb-3" style="font-family: 'Playfair Display', serif;"><i class="fas fa-exchange-alt text-gold me-2"></i>2. Jenis Retur & Penukaran</h5>
                        <ul class="ps-3 mb-4">
                            <li><strong>Cacat Produksi / Salah Kirim:</strong> Jika kerusakan diakibatkan oleh pihak kami, kami akan menukar dengan produk baru yang sama dan menanggung 100% ongkir retur bolak-balik.</li>
                            <li><strong>Tukar Ukuran (Size Exchange):</strong> Apabila pelanggan ingin menukar ukuran (kebesaran/kekecilan), penukaran diperbolehkan selama stok ukuran pengganti tersedia. Ongkir retur sepenuhnya ditanggung oleh pihak pembeli.</li>
                        </ul>

                        <h5 class="fw-bold text-dark mt-4 mb-3" style="font-family: 'Playfair Display', serif;"><i class="fas fa-hand-holding-usd text-gold me-2"></i>3. Proses Refund (Pengembalian Uang)</h5>
                        <ul class="ps-3 mb-4">
                            <li>Refund hanya berlaku jika produk cacat produksi atau salah kirim dan stok produk pengganti kosong.</li>
                            <li>Refund akan ditransfer ke rekening bank pembeli setelah produk yang diretur sampai di gudang kami dan lolos inspeksi kualitas tim QC.</li>
                            <li>Proses verifikasi QC dan transfer refund memakan waktu 2-3 hari kerja sejak produk retur tiba.</li>
                        </ul>

                        <h5 class="fw-bold text-dark mt-4 mb-3" style="font-family: 'Playfair Display', serif;"><i class="fas fa-headset text-gold me-2"></i>4. Cara Mengajukan Retur</h5>
                        <p class="mb-4">Untuk mengajukan retur, silakan hubungi Customer Service kami melalui tombol WhatsApp yang melayang di pojok kanan bawah, kirimkan nomor kode pesanan, alasan retur, foto detail produk, serta lampirkan video unboxing Anda. Tim kami akan melayani Anda segera.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
