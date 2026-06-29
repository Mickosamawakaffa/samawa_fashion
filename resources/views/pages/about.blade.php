@extends('layouts.frontend')

@section('title', 'Tentang Kami - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <!-- Section Header -->
        <div class="section-title" data-aos="fade-up">
            <h2>Kisah SAMAWA</h2>
            <div class="divider"></div>
        </div>

        <!-- Story Section -->
        <div class="row align-items-center mb-5">
            <div class="col-lg-6" data-aos="fade-right">
                <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=800" alt="Samawa Fashion Story" class="img-fluid rounded shadow-lg">
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0" data-aos="fade-left">
                <h3 class="mb-3 text-gold" style="color: var(--gold-color);">Elegansi & Kemewahan Sejati</h3>
                <p class="text-muted">Didirikan dengan hasrat mendalam untuk menghadirkan keindahan sejati, SAMAWA Fashion hadir sebagai lambang kemewahan dan keanggunan. Kami percaya bahwa busana bukan sekadar pakaian, melainkan refleksi dari kepribadian yang berkelas.</p>
                <p class="text-muted">Setiap helai karya kami dirancang secara eksklusif dengan memperhatikan detail terkecil, menggunakan bahan berkualitas premium paling halus, serta sentuhan jahitan yang sempurna untuk menunjang penampilan Anda dalam momen berharga.</p>
            </div>
        </div>

        <!-- Visi & Misi Section -->
        <div class="row bg-white p-5 rounded shadow-sm mb-5 text-center" data-aos="fade-up">
            <div class="col-md-6 mb-4 mb-md-0 border-end border-light">
                <i class="fas fa-eye fa-3x text-gold mb-3" style="color: var(--gold-color);"></i>
                <h4 class="mb-3">Visi Kami</h4>
                <p class="text-muted mx-auto" style="max-width: 400px;">Menjadi butik fashion luxury terdepan di Indonesia yang menginspirasi keanggunan, keberanian, dan kemewahan dalam berbusana bagi setiap kalangan berkelas.</p>
            </div>
            <div class="col-md-6">
                <i class="fas fa-bullseye fa-3x text-gold mb-3" style="color: var(--gold-color);"></i>
                <h4 class="mb-3">Misi Kami</h4>
                <p class="text-muted mx-auto" style="max-width: 400px;">Menyediakan busana berkualitas premium dengan desain orisinal yang timeless, memberikan pengalaman berbelanja personal yang tak terlupakan, serta terus mengutamakan kualitas kerajinan tangan lokal dengan standar dunia.</p>
            </div>
        </div>

        <!-- Team Section -->
        <div class="section-title mt-5" data-aos="fade-up">
            <h2>Tim Kami</h2>
            <div class="divider"></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4 text-center mb-4" data-aos="fade-up" data-aos-delay="100">
                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=300" alt="CEO" class="rounded-circle mb-3 shadow" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid var(--gold-color);">
                <h5>Karin Widjaja</h5>
                <p class="text-gold" style="color: var(--gold-color);">Founder & Creative Director</p>
            </div>
            <div class="col-md-4 text-center mb-4" data-aos="fade-up" data-aos-delay="200">
                <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=300" alt="Designer" class="rounded-circle mb-3 shadow" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid var(--gold-color);">
                <h5>Adrian Pratama</h5>
                <p class="text-gold" style="color: var(--gold-color);">Head of Design</p>
            </div>
            <div class="col-md-4 text-center mb-4" data-aos="fade-up" data-aos-delay="300">
                <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?w=300" alt="Marketing" class="rounded-circle mb-3 shadow" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid var(--gold-color);">
                <h5>Sofia Wijaya</h5>
                <p class="text-gold" style="color: var(--gold-color);">Operations Manager</p>
            </div>
        </div>
    </div>
</div>
@endsection
