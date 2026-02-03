@extends('layouts.Frontend.app')

@section('title', 'Daftar Prestasi')

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    /* Global Variables */
    :root {
        --primary-gradient: linear-gradient(135deg, #014A36 0%, #007F5F 100%);
        --light-green: #007F5F; 
        --text-primary: #2d3748;
        --text-secondary: #718096;
        --card-bg: #ffffff;
        --shadow-light: 0 10px 40px rgba(0, 0, 0, 0.08);
        --shadow-heavy: 0 20px 60px rgba(0, 0, 0, 0.15);
        --border-radius: 24px;
        --transition-smooth: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* Hero Section */
    .hero-profile {
        width: 100%; height: 400px; background-image: url('{{ asset('storage/images/slider/testimoni.png') }}');
        background-position: center; background-size: cover; display: flex; flex-direction: column;
        align-items: center; justify-content: center; text-align: center; color: #fff;
        position: relative; padding: 20px;
    }
    .hero-profile::before {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.5); z-index: 1;
    }
    .hero-profile .hero-content { position: relative; z-index: 2; }
    .hero-profile .main-title {
        font-family: 'Playfair Display', serif; font-size: clamp(4.5rem, 10vw, 6rem); font-weight: 700;
        margin-bottom: 15px; line-height: 1.2; color: #fff; text-shadow: 2px 2px 10px rgba(0,0,0,0.7);
    }
    .hero-profile .sub-title {
        font-family: 'Roboto', sans-serif; font-size: clamp(1.5rem, 5vw, 2.5rem); font-weight: 400;
        opacity: 0.95; color: #fff; text-shadow: 1px 1px 7px rgba(0,0,0,0.6);
    }
    
    /* Prestasi Content Area */
    .prestasi-area {
        position: relative; background: #f8fafc;
        padding: 80px 0; overflow: hidden;
    }
    .prestasi-area .title-default-left {
        font-size: clamp(2.5rem, 5vw, 3.5rem); font-weight: 800; margin-bottom: 25px;
        position: relative; display: inline-block; background: var(--primary-gradient);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        letter-spacing: -0.02em;
    }
    .prestasi-area .title-default-left::after {
        content: ''; position: absolute; bottom: -10px; left: 0; width: 80px; height: 6px;
        background: linear-gradient(135deg, #00C9A7 0%, #00E0A6 100%); border-radius: 3px;
    }

    /* --- Filter Section --- */
    .filter-section {
        background: var(--card-bg);
        padding: 2rem 2.5rem;
        border-radius: var(--border-radius);
        box-shadow: 0 15px 50px rgba(0, 80, 50, 0.1);
        margin-bottom: 50px;
        border: 1px solid #e2e8f0;
    }
    .filter-section .filter-title {
        font-family: 'Inter', sans-serif; font-size: 1.5rem; font-weight: 600;
        color: var(--light-green); margin-bottom: 1.5rem; text-align: center;
    }
    .filter-form {
        display: grid; grid-template-columns: 1fr 1fr auto; gap: 1.5rem; align-items: center;
    }
    
    /* --- Styles untuk Custom Dropdown (Pengganti Select Biru) --- */
    .filter-group { position: relative; }

    /* Input Biasa */
    .filter-group .form-control {
        width: 100%; height: 55px; padding: 10px 20px 10px 50px;
        border: 2px solid #e2e8f0; border-radius: 12px;
        font-size: 1.25rem; font-weight: 500; color: #333;
        transition: all 0.3s; appearance: none; background-color: #ffffff;
    }
    .filter-group .form-control:focus {
        border-color: var(--light-green); box-shadow: 0 0 0 4px rgba(0, 127, 95, 0.15); outline: none;
    }
    .filter-group .icon {
        position: absolute; left: 18px; top: 50%; transform: translateY(-50%);
        color: var(--light-green); font-size: 1.3rem; z-index: 5; pointer-events: none;
    }

    /* CUSTOM SELECT WRAPPER */
    .custom-select-wrapper {
        position: relative; user-select: none; width: 100%;
    }
    
    /* Tampilan Utama (Trigger) */
    .custom-select-trigger {
        position: relative; display: flex; align-items: center; justify-content: space-between;
        width: 100%; height: 55px; padding: 10px 20px 10px 50px;
        border: 2px solid #e2e8f0; border-radius: 12px;
        background: #fff; cursor: pointer; transition: all 0.3s;
        font-size: 1.25rem; font-weight: 500; color: #333;
    }
    .custom-select-trigger:hover { border-color: #cbd5e0; }
    
    /* Saat dropdown terbuka */
    .custom-select-wrapper.open .custom-select-trigger {
        border-color: var(--light-green);
        box-shadow: 0 0 0 4px rgba(0, 127, 95, 0.15);
        border-radius: 12px 12px 0 0; /* Sudut bawah jadi tajam saat buka */
    }
    .custom-select-wrapper.open .arrow { transform: rotate(180deg); }

    /* Panah Custom */
    .arrow {
        font-size: 0.9rem; color: var(--light-green); transition: transform 0.3s;
    }

    /* Daftar Pilihan (Dropdown List) */
    .custom-options {
        position: absolute; display: block; top: 100%; left: 0; right: 0;
        border: 2px solid var(--light-green); border-top: 0;
        border-radius: 0 0 12px 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        background: #fff; z-index: 100;
        opacity: 0; visibility: hidden; pointer-events: none;
        transform: translateY(-10px); transition: all 0.3s ease;
        max-height: 250px; overflow-y: auto;
    }
    
    /* Animasi saat muncul */
    .custom-select-wrapper.open .custom-options {
        opacity: 1; visibility: visible; pointer-events: auto; transform: translateY(0);
    }

    /* Item Pilihan */
    .custom-option {
        padding: 12px 20px; font-size: 1.1rem; color: #333; cursor: pointer;
        transition: all 0.2s; border-bottom: 1px solid #f0f0f0;
    }
    .custom-option:last-child { border-bottom: none; }

    /* HOVER STATE: HIJAU (Solusi Masalah Biru) */
    .custom-option:hover, 
    .custom-option.selected {
        background-color: var(--light-green);
        color: #fff; /* Teks jadi putih saat di hover */
    }

    /* Tombol Cari */
    .btn-filter {
        height: 55px; padding: 0 40px;
        background: var(--primary-gradient); color: white;
        border: none; border-radius: 12px; font-weight: 600; font-size: 1.1rem;
        cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-filter:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0, 127, 95, 0.25); }

    /* Card & Lainnya */
    .prestasi-card {
        background: var(--card-bg); border-radius: 24px; overflow: hidden;
        box-shadow: var(--shadow-light); transition: all 0.4s; text-align: center; margin-bottom: 30px;
    }
    .prestasi-card:hover { transform: translateY(-10px); box-shadow: var(--shadow-heavy); }
    .card-image { position: relative; height: 250px; overflow: hidden; }
    .card-image img { width: 100%; height: 100%; object-fit: cover; transition: all 0.4s; }
    .prestasi-card:hover .card-image img { transform: scale(1.1); }
    .card-content { padding: 30px 25px; }
    .card-title { font-family: 'Inter', sans-serif; font-size: 1.6rem; font-weight: 600; color: var(--light-green); margin-bottom: 10px; line-height: 1.3; }
    .card-date { font-family: 'Inter', sans-serif; font-size: 1rem; color: var(--text-secondary); font-weight: 500; }
    
    .empty-state { text-align: center; padding: 50px 20px; background: #fff; border-radius: 20px; box-shadow: var(--shadow-light); }
    .empty-icon { font-size: 4rem; color: var(--light-green); margin-bottom: 20px; }

    /* Responsive */
    @media (max-width: 991px) { .filter-form { grid-template-columns: 1fr; } }
    @media (max-width: 768px) { .prestasi-area { padding: 60px 0; } }
</style>
@endsection

@section('content')
<section class="hero-profile">
    <div class="hero-content">
        <h1 class="main-title">Prestasi Membanggakan</h1>
        <p class="sub-title">Pencapaian luar biasa dari siswa dan siswi terbaik kami</p>
    </div>
</section>

<section class="prestasi-area">
    <div class="container">
        <h2 class="title-default-left text-center d-block">Galeri Prestasi</h2>

        <div class="filter-section">
            <h4 class="filter-title">Cari Prestasi</h4>
            <form action="{{ route('frontend.prestasi') }}" method="GET" class="filter-form" id="prestasiForm">
                
                <div class="filter-group">
                    <i class="fas fa-search icon"></i>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama prestasi..." value="{{ request('search') }}">
                </div>

                <div class="filter-group">
                    <i class="fas fa-medal icon"></i>
                    
                    <input type="hidden" name="tingkat" id="inputTingkat" value="{{ request('tingkat') }}">
                    
                    <div class="custom-select-wrapper" id="customSelect">
                        <div class="custom-select-trigger">
                            <span id="selectText">
                                {{ request('tingkat') ? 'Tingkat '.request('tingkat') : 'Semua Tingkat' }}
                            </span>
                            <i class="fas fa-chevron-down arrow"></i>
                        </div>
                        <div class="custom-options">
                            <div class="custom-option {{ request('tingkat') == '' ? 'selected' : '' }}" data-value="">Semua Tingkat</div>
                            
                            @foreach($daftarTingkat as $tingkat)
                                <div class="custom-option {{ request('tingkat') == $tingkat ? 'selected' : '' }}" 
                                     data-value="{{ $tingkat }}">
                                    Tingkat {{ $tingkat }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i>
                    <span>Cari</span>
                </button>
            </form>
        </div>

        @if($prestasi->count() > 0)
            <div class="row g-4">
                @foreach($prestasi as $item)
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('frontend.prestasi.detail', $item->slug) }}" class="text-decoration-none d-block">
                            <div class="prestasi-card">
                                <div class="card-image">
                                    @if($item->gambar)
                                        <img src="{{ asset('storage/'.$item->gambar) }}" alt="{{ $item->judul }}" loading="lazy">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" alt="No Image" loading="lazy">
                                    @endif
                                </div>
                                <div class="card-content">
                                    <h3 class="card-title">{{ $item->judul }}</h3>
                                    <div class="card-date">
                                        <span>{{ \Carbon\Carbon::parse($item->tanggal_prestasi)->translatedFormat('d F Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrapper mt-5 d-flex justify-content-center">
                {{ $prestasi->appends(request()->query())->links() }}
            </div>

        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-search-minus"></i>
                </div>
                <h3>Prestasi Tidak Ditemukan</h3>
                <p>Maaf, tidak ada prestasi yang cocok dengan kriteria pencarian Anda.</p>
            </div>
        @endif
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.querySelector('.custom-select-wrapper');
        const trigger = document.querySelector('.custom-select-trigger');
        const customOptions = document.querySelectorAll('.custom-option');
        const hiddenInput = document.getElementById('inputTingkat');
        const selectText = document.getElementById('selectText');

        // Toggle buka/tutup dropdown
        trigger.addEventListener('click', function () {
            wrapper.classList.toggle('open');
        });

        // Saat opsi dipilih
        customOptions.forEach(option => {
            option.addEventListener('click', function () {
                // Hapus kelas selected dari semua opsi
                customOptions.forEach(opt => opt.classList.remove('selected'));
                // Tambah kelas selected ke yang diklik
                this.classList.add('selected');
                
                // Ambil nilai dan teks
                const value = this.getAttribute('data-value');
                const text = this.textContent;

                // Update tampilan dan input hidden
                selectText.textContent = text;
                hiddenInput.value = value;
                
                // Tutup dropdown
                wrapper.classList.remove('open');
            });
        });

        // Tutup dropdown jika klik di luar area
        document.addEventListener('click', function (e) {
            if (!wrapper.contains(e.target)) {
                wrapper.classList.remove('open');
            }
        });
    });
</script>
@endsection