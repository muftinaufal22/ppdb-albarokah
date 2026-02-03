@extends('layouts.Frontend.app')

@section('title')
    Detail Prestasi - {{ $prestasi->judul }}
@endsection

@section('head')
{{-- Memastikan Font Awesome dimuat untuk ikon --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* Global Variables */
    :root {
        --primary-color: #007F5F;
        --secondary-color: #014A36;
        --accent-color: #a8e063;
        --text-dark: #2c3e50;
        --text-light: #5f6c7b; 
        --bg-light: #f7f8fc; 
        --card-bg: #ffffff;
        --border-color: #eef2f7;
        --border-radius-lg: 16px; 
        --transition-speed: 0.4s ease;
    }

    /* Animasi Fade In */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animated { animation: 0.8s ease-out 0s 1 both fadeInUp; }

    /* Latar Belakang Utama */
    .detail-area-prestasi {
        background-color: var(--bg-light);
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23014a36' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        padding: 80px 0; 
        min-height: 85vh; 
        overflow-x: hidden;
    }

    .detail-box-wrapper { max-width: 1200px; margin: 0 auto; padding: 0 15px; }
    
    /* --- PENYEMPURNAAN: Kotak Konten Utama --- */
    .detail-box-content {
        background-color: var(--card-bg);
        padding: 40px 50px 50px 50px; /* Padding disesuaikan */
        border-radius: var(--border-radius-lg);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08); /* Shadow lebih halus */
        display: flex;
        flex-wrap: wrap;
        gap: 50px;
        align-items: flex-start;
        border-top: 4px solid var(--primary-color); /* Aksen garis atas */
    }
    
    /* --- PENYEMPURNAAN: Tampilan Gambar --- */
    .detail-image-column { flex: 1 1 45%; min-width: 350px; }
    .article-image img {
        width: 100%;
        height: auto;
        border-radius: 12px; /* Radius internal lebih kecil dari container */
        display: block;
        transition: transform var(--transition-speed);
        box-shadow: 0 10px 35px rgba(0,0,0,0.1); /* Bayangan modern */
    }
    .article-image img:hover {
        transform: scale(1.02);
    }
    .article-image figcaption {
        text-align: center;
        margin-top: 16px;
        font-size: 0.9em;
        color: var(--text-light);
    }

    /* Kolom Deskripsi */
    .detail-description-column { flex: 1 1 50%; min-width: 300px; }
    
    /* Judul dengan Efek Gradien */
    .article-title {
        font-weight: 800;
        margin-bottom: 20px; /* Jarak disesuaikan */
        font-size: clamp(32px, 4vw, 40px);
        line-height: 1.25;
        background: linear-gradient(45deg, var(--primary-color) 30%, var(--secondary-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Info Box dengan Ikon Dekoratif */
    .article-info-description {
        background-color: var(--bg-light);
        border-left: 4px solid var(--primary-color);
        padding: 20px 25px;
        margin-bottom: 35px; /* Jarak disesuaikan */
        border-radius: 8px;
        font-size: 1em;
        line-height: 1.7;
        position: relative;
        overflow: hidden;
    }
    .article-info-description::after {
        content: '\f559';
        font-family: "Font Awesome 6 Free"; font-weight: 900;
        position: absolute; top: 50%; right: 15px;
        transform: translateY(-50%) rotate(-15deg);
        font-size: 4.5em; color: rgba(1, 74, 54, 0.07); z-index: 0;
    }
    .article-info-description p { margin-bottom:0; position: relative; z-index: 1; color: var(--text-dark); }
    .article-info-description strong { color: var(--primary-color); }

    /* --- PENYEMPURNAAN: Judul Deskripsi dengan Aksen --- */
    .description-heading {
        font-size: 1.5em;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 25px;
        position: relative;
        padding-bottom: 10px;
    }
    .description-heading::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: var(--primary-color);
        border-radius: 2px;
    }
    
    .article-body { font-size: 1.1em; line-height: 1.8; color: var(--text-dark); text-align: justify; }
    
    /* Tombol Kembali */
    .back-button-container { padding-top: 50px; text-align: center; }
    .btn-back { padding: 14px 35px; background-image: linear-gradient(45deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; text-decoration: none; border-radius: 50px; display: inline-block; transition: all var(--transition-speed); font-weight: 600; border: none; box-shadow: 0 4px 20px rgba(1, 74, 54, 0.3); }
    .btn-back:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(1, 74, 54, 0.4); }

    /* Responsive */
    @media (max-width: 992px) {
        .detail-box-content { flex-direction: column; padding: 30px; }
    }
    @media (max-width: 576px) {
        .detail-box-content { padding: 25px; }
        .article-title { font-size: 28px; }
        .description-heading { font-size: 1.3em; }
    }
</style>
@endsection

@section('content')
<section class="detail-area-prestasi">
    <div class="detail-box-wrapper"> 
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="detail-box-content">
                    
                    {{-- Kolom Gambar --}}
                    <div class="detail-image-column animated" style="animation-delay: 0.2s;">
                        <figure class="article-image">
                            @if($prestasi->gambar)
                                <img src="{{ asset('storage/'.$prestasi->gambar) }}" alt="{{ $prestasi->judul }}">
                                <figcaption>Dokumentasi Prestasi</figcaption>
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="No Image" style="opacity: 0.5;">
                                <figcaption>Tidak Ada Dokumentasi</figcaption>
                            @endif
                        </figure>
                    </div>

                    {{-- Kolom Deskripsi --}}
                    <div class="detail-description-column animated" style="animation-delay: 0.4s;">
                        <article class="prestasi-article">
                            <h1 class="article-title">{{ $prestasi->judul }}</h1>
                            <div class="article-info-description">
                                <p>
                                    Prestasi tingkat <strong>{{ $prestasi->tingkat }}</strong> ini berhasil diraih oleh <strong>{{ $prestasi->peraih }}</strong> pada tanggal <strong>{{ \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->translatedFormat('j F Y') }}</strong>.
                                    @if($prestasi->penyelenggara)
                                        Kompetisi ini diselenggarakan oleh <strong>{{ $prestasi->penyelenggara }}</strong>.
                                    @endif
                                </p>
                            </div>
                            <h5 class="description-heading">Deskripsi Prestasi</h5>
                            <div class="article-body">
                                {!! nl2br(e($prestasi->deskripsi)) !!}
                            </div>
                        </article>
                    </div> 
                </div> 

                {{-- Tombol Kembali --}}
                <div class="back-button-container animated" style="animation-delay: 0.6s;">
                    <a href="{{ route('frontend.prestasi') }}" class="btn-back">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Prestasi
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Prestasi Terkait --}}
        {{-- ... (kode prestasi terkait tidak perlu diubah) ... --}}

    </div> 
</section>
@endsection