@extends('layouts.frontend')

@section('title', 'Hubungi Kami - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <!-- Section Header -->
        <div class="section-title" data-aos="fade-up">
            <h2>Hubungi Kami</h2>
            <div class="divider"></div>
        </div>

        <div class="row">
            <!-- Contact Info -->
            <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right">
                <div class="card p-4 h-100 shadow-sm border-0">
                    <h3 class="mb-4 text-gold" style="color: var(--gold-color);">Samawa Fashion Gallery</h3>
                    <p class="text-muted mb-4">Butik kami terbuka untuk kunjungan langsung. Rasakan kemewahan koleksi kami secara langsung dengan layanan private styling consultation.</p>
                    
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <i class="fas fa-map-marker-alt fa-2x text-gold" style="color: var(--gold-color); width: 30px;"></i>
                        </div>
                        <div>
                            <h5>Alamat</h5>
                            <p class="text-muted">Jl. Kemang Raya No. 45, Jakarta Selatan, 12730</p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <i class="fas fa-phone-alt fa-2x text-gold" style="color: var(--gold-color); width: 30px;"></i>
                        </div>
                        <div>
                            <h5>Telepon</h5>
                            <p class="text-muted">+62 812 3456 7890<br>(Senin - Sabtu: 09.00 - 18.00)</p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <i class="fas fa-envelope fa-2x text-gold" style="color: var(--gold-color); width: 30px;"></i>
                        </div>
                        <div>
                            <h5>Email</h5>
                            <p class="text-muted">info@samawafashion.com</p>
                        </div>
                    </div>

                    <div class="d-flex mt-4 footer-social">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7" data-aos="fade-left">
                <div class="card p-4 shadow-sm border-0">
                    <h3 class="mb-4">Kirim Pesan</h3>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Masukkan email Anda" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Pesan Anda <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" rows="5" class="form-control @error('message') is-invalid @enderror" placeholder="Tuliskan pesan atau pertanyaan Anda di sini..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-gold w-100">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
