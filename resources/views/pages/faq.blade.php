@extends('layouts.frontend')

@section('title', 'Frequently Asked Questions (FAQ) - Samawa Fashion')

@section('content')
<div class="py-5" style="background-color: #FAF6F0;">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2 style="font-family: 'Playfair Display', serif; font-weight: bold;">Frequently Asked Questions (FAQ)</h2>
            <p class="text-muted small">Temukan jawaban atas pertanyaan umum seputar belanja di Samawa Fashion</p>
            <div class="divider mx-auto" style="width: 80px; height: 3px; background-color: var(--gold-color);"></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="accordion shadow-sm" id="faqAccordion" style="border-radius: 10px; overflow: hidden; border: none;">
                    
                    <!-- FAQ Item 1 -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button bg-white text-dark fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <i class="fas fa-question-circle text-gold me-2"></i> Bagaimana cara memesan produk di Samawa Fashion?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light text-muted" style="line-height: 1.7;">
                                Buka halaman katalog produk, pilih produk busana muslimah pilihan Anda, pilih ukuran dan warna yang sesuai, lalu klik <strong>Tambah ke Keranjang</strong>. Setelah selesai memilih, buka menu keranjang belanja, masukkan voucher (jika ada), dan klik <strong>Checkout Sekarang</strong> untuk melengkapi data penerima, ongkir kurir, dan metode pembayaran.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed bg-white text-dark fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="fas fa-question-circle text-gold me-2"></i> Apa saja metode pembayaran yang didukung?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light text-muted" style="line-height: 1.7;">
                                Kami mendukung metode pembayaran transfer bank manual (BCA, BRI, Mandiri), pembayaran online otomatis instan menggunakan Midtrans (Virtual Account, Gopay, QRIS, dll), serta Cash on Delivery (COD) di mana pembayaran diserahkan langsung ke kurir saat barang sampai.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed bg-white text-dark fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <i class="fas fa-question-circle text-gold me-2"></i> Berapa lama estimasi waktu pengiriman paket?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light text-muted" style="line-height: 1.7;">
                                Waktu pengiriman bergantung pada lokasi tujuan Anda. Secara umum untuk wilayah Pulau Jawa memakan waktu 2-3 hari kerja, sedangkan luar Pulau Jawa memakan waktu 3-5 hari kerja menggunakan ekspedisi JNE, J&T, atau SiCepat.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed bg-white text-dark fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                <i class="fas fa-question-circle text-gold me-2"></i> Apakah saya bisa menukar ukuran pakaian yang dibeli?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light text-muted" style="line-height: 1.7;">
                                Ya, Anda diperbolehkan menukar ukuran pakaian maksimal 7 hari sejak barang diterima, selama stok ukuran pengganti tersedia dan produk belum pernah dipakai/dicuci. Ongkir pengiriman retur ditanggung oleh pelanggan.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed bg-white text-dark fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                <i class="fas fa-question-circle text-gold me-2"></i> Bagaimana cara melacak status pengiriman pesanan saya?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light text-muted" style="line-height: 1.7;">
                                Setelah admin menginput nomor resi resmi, Anda dapat membuka halaman detail pesanan di akun Anda. Di situ akan muncul tombol <strong>Lacak Paket</strong> yang langsung terhubung ke tautan pelacakan resmi kurir yang Anda pilih.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 6 -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingSix">
                            <button class="accordion-button collapsed bg-white text-dark fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                <i class="fas fa-question-circle text-gold me-2"></i> Berapa lama batas waktu pembayaran untuk transfer manual?
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light text-muted" style="line-height: 1.7;">
                                Batas waktu pembayaran transfer manual adalah <strong>24 jam</strong> semenjak pesanan Anda buat. Apabila Anda tidak melakukan transfer dan mengunggah bukti pembayaran dalam kurun waktu tersebut, sistem kami akan membatalkan pesanan secara otomatis.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 7 -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingSeven">
                            <button class="accordion-button collapsed bg-white text-dark fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                <i class="fas fa-question-circle text-gold me-2"></i> Apakah Samawa Fashion mendukung pengembalian dana (Refund)?
                            </button>
                        </h2>
                        <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light text-muted" style="line-height: 1.7;">
                                Pengembalian dana (Refund) hanya dapat dilakukan apabila produk yang Anda terima mengalami cacat produksi dari pihak kami atau salah dikirim jenisnya, sementara stok produk pengganti kosong di gudang kami.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 8 -->
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header" id="headingEight">
                            <button class="accordion-button collapsed bg-white text-dark fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                <i class="fas fa-question-circle text-gold me-2"></i> Bagaimana cara menggunakan kode voucher diskon?
                            </button>
                        </h2>
                        <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light text-muted" style="line-height: 1.7;">
                                Masukkan kode voucher diskon pada kolom <strong>Kode Voucher</strong> di halaman Keranjang Belanja Anda, lalu klik tombol <strong>Pakai</strong>. Sistem akan langsung memotong subtotal belanja Anda di keranjang jika kode voucher valid.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
