@extends('layouts.Frontend.app')

@section('title')
Berita & Kegiatan
@endsection

{{-- SECTION: Penambahan CSS dan Font dari halaman Prestasi --}}
@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    /* Global Variables */
    :root {
        --primary-gradient: linear-gradient(135deg, #014A36 0%, #007F5F 100%);
        --secondary-gradient: linear-gradient(135deg, #a8e063 0%, #56ab2f 100%);
        --accent-gradient: linear-gradient(135deg, #00C9A7 0%, #00E0A6 100%);
        --text-primary: #2d3748;
        --text-secondary: #718096;
        --card-bg: rgba(255, 255, 255, 0.95);
        --shadow-light: 0 10px 40px rgba(0, 0, 0, 0.1);
        --shadow-heavy: 0 20px 60px rgba(0, 0, 0, 0.2);
        --border-radius: 24px;
        --transition-smooth: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        --glow-color: rgba(1, 74, 54, 0.4);
    }

    /* Hero Section */
    .hero-profile {
        width: 100%;
        height: 400px;
        /* GANTI GAMBAR: Ubah path gambar background di sini jika diperlukan */
        background-image: url('{{ asset('storage/images/slider/testimoni.png') }}');
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
        position: relative;
        padding: 20px;
    }
    
    .hero-profile::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Overlay gelap untuk keterbacaan teks */
        z-index: 1;
    }

    .hero-profile .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-profile .main-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(4rem, 8vw, 5.5rem);
        font-weight: 700;
        margin-bottom: 15px;
        line-height: 1.2;
        color: #fff;
        text-shadow: 2px 2px 10px rgba(0,0,0,0.7);
        animation: fadeInDown 1.5s forwards;
    }

    .hero-profile .sub-title {
        font-family: 'Inter', sans-serif;
        font-size: clamp(1.2rem, 4vw, 1.8rem);
        font-weight: 400;
        opacity: 0.95;
        color: #fff;
        text-shadow: 1px 1px 7px rgba(0,0,0,0.6);
        animation: fadeInUp 1.5s forwards;
    }

    @keyframes fadeInDown {
        0% { opacity: 0; transform: translateY(-50px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(50px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    /* Content Area */
    .content-area {
        position: relative;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 80px 0;
        overflow: hidden;
    }

    .content-area .title-default-left {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 800;
        margin-bottom: 25px;
        position: relative;
        display: inline-block;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.02em;
    }

    .content-area .title-default-left::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 0;
        width: 80px;
        height: 6px;
        background: var(--accent-gradient);
        border-radius: 3px;
    }

    /* Styling untuk Form Pencarian */
    .search-form-section {
        background: var(--card-bg);
        padding: 25px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-light);
        margin-bottom: 60px;
        backdrop-filter: blur(10px);
    }

    .search-form-section .form-inline {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }
    .search-form-section .form-control {
        height: 45px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }
    .search-form-section .btn {
        height: 45px;
        border-radius: 8px;
        padding: 0 25px;
    }

    /* Enhanced Card Design */
    .item-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-light);
        transition: var(--transition-smooth);
        margin-bottom: 30px;
        position: relative;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        flex-direction: column;
        height: 100%; /* Membuat semua card sama tinggi */
    }

    .item-card:hover {
        transform: translateY(-15px);
        box-shadow: var(--shadow-heavy), 0 0 40px var(--glow-color);
    }
    
    .item-card a {
        text-decoration: none;
    }

    .card-image {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition-smooth);
    }

    .item-card:hover .card-image img {
        transform: scale(1.1);
    }

    .card-content {
        padding: 20px 25px;
        text-align: left;
        flex-grow: 1; /* Konten akan mengisi sisa ruang */
        display: flex;
        flex-direction: column;
    }

    .card-title {
        font-family: 'Inter', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 12px;
        line-height: 1.4;
        flex-grow: 1; /* Judul akan mengisi ruang agar meta info rata bawah */
    }

    .card-title a {
        color: var(--text-primary);
    }
    .card-title a:hover {
        color: #007F5F;
    }
    
    .card-meta {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-weight: 500;
        margin-top: auto; /* Mendorong meta info ke bagian bawah card */
    }

    .card-meta span {
        display: inline-flex;
        align-items: center;
    }

    .card-meta i {
        margin-right: 6px;
        color: #007F5F;
    }
    
    /* Empty State (jika tidak ada data) */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        width: 100%;
    }
    .empty-state .empty-icon {
        font-size: 5rem;
        color: #007F5F;
        opacity: 0.5;
        margin-bottom: 20px;
    }
    .empty-state h3 {
        font-size: 1.8rem;
        color: var(--text-primary);
    }
    .empty-state p {
        font-size: 1.1rem;
        color: var(--text-secondary);
    }
</style>
@endsection

@section('content')
{{-- SECTION: Hero - Menggantikan Judul Halaman Lama --}}
<section class="hero-profile">
    <div class="hero-content">
        <h1 class="main-title">Berita & Kegiatan</h1>
        <p class="sub-title">Ikuti informasi dan aktivitas terbaru dari kami</p>
    </div>
</section>

{{-- SECTION: Content Area - Menggantikan .news-page-area --}}
<section class="content-area">
    <div class="container">
        
        {{-- SECTION: Form Pencarian dengan gaya baru --}}
        <div class="search-form-section">
             <form action="{{ route('berita') }}" method="GET" class="form-inline justify-content-center">
                <div class="form-group flex-grow-1">
                    <input type="text" name="search" class="form-control w-100" placeholder="Cari berita atau kegiatan..." value="{{ $search ?? '' }}">
                </div>
                <div class="form-group">
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal ?? '' }}">
                </div>
                <button type="submit" class="btn btn-primary">Cari</button>
                @if($search || $tanggal)
                    <a href="{{ route('berita') }}" class="btn btn-secondary">Reset</a>
                @endif
            </form>
        </div>

        @if ($paginatedItems->count() > 0)
            <div class="row">
                @foreach ($paginatedItems as $item)
                <div class="col-lg-4 col-md-6">
                    {{-- CARD CONTAINER: Menggunakan struktur card baru --}}
                    <div class="item-card">
                        {{-- Link untuk keseluruhan card --}}
                        <a href="{{ $item->type == 'kegiatan' ? route('detail.kegiatan', $item->id) : route('detail.berita', $item->slug) }}">
                            <div class="card-image">
                                @if($item->type == 'kegiatan')
                                <img src="{{asset('storage/images/kegiatan/' .$item->thumbnail)}}" alt="{{ $item->title }}" loading="lazy">
                                @else
                                <img src="{{asset('storage/images/berita/' .$item->thumbnail)}}" alt="{{ $item->title }}" loading="lazy">
                                @endif
                            </div>
                        </a>
                        <div class="card-content">
                            <h3 class="card-title">
                                <a href="{{ $item->type == 'kegiatan' ? route('detail.kegiatan', $item->id) : route('detail.berita', $item->slug) }}">
                                    {{$item->title}}
                                    {{-- BADGE BARU: Tampilan badge baru untuk item 'kegiatan' --}}
                                    @if($item->type == 'kegiatan' && $item->is_new)
                                        <span class="badge badge-danger ml-2">Baru</span>
                                    @endif
                                </a>
                            </h3>
                            
                            {{-- META INFO: Tanggal, author, kategori --}}
                            <div class="card-meta">
                                @if($item->type == 'kegiatan')
                                <span><i class="fa fa-calendar" aria-hidden="true"></i> {{Carbon\Carbon::parse($item->tanggal)->format('d M Y')}}</span>
                                <span class="ml-3"><i class="fa fa-tag" aria-hidden="true"></i> Kegiatan</span>
                                @else
                                <span><i class="fa fa-user" aria-hidden="true"></i> {{$item->user->name}}</span>
                                <span class="ml-3"><i class="fa fa-tags" aria-hidden="true"></i> {{$item->kategori->nama}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            {{-- EMPTY STATE: Tampilan ketika tidak ada data ditemukan --}}
            <div class="row">
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Tidak Ditemukan</h3>
                        <p>Tidak ada berita atau kegiatan yang sesuai dengan kriteria pencarian Anda.</p>
                         @if($search || $tanggal)
                            <a href="{{ route('berita') }}" class="btn btn-primary mt-3">Reset Pencarian</a>
                         @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- SECTION: Pagination --}}
        <div class="row">
            <div class="col-12 d-flex justify-content-center mt-4">
                {{-- Gunakan view pagination default atau custom Anda --}}
                {{ $paginatedItems->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</section>
@endsection