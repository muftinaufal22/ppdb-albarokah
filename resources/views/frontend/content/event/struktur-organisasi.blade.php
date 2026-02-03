@extends('layouts.Frontend.app')

@section('title', 'Struktur Organisasi')

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
        --light-green: #007F5F;
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
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1;
    }

    .hero-profile .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-profile .main-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(3.5rem, 8vw, 5rem);
        font-weight: 700;
        margin-bottom: 15px;
        line-height: 1.2;
        color: #fff;
        text-shadow: 2px 2px 10px rgba(0,0,0,0.7);
        animation: fadeInDown 1.5s forwards;
    }

    .hero-profile .sub-title {
        font-family: 'Inter', sans-serif;
        font-size: clamp(1.2rem, 4vw, 2rem);
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
    .org-area {
        position: relative;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 80px 0;
        overflow: hidden;
    }

    /* Decorative patterns on sides */
    .org-area::before,
    .org-area::after {
        content: '';
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 200px;
        height: 60%;
        opacity: 0.15;
        background-image: 
            repeating-linear-gradient(
                45deg,
                var(--light-green),
                var(--light-green) 10px,
                transparent 10px,
                transparent 20px
            );
        z-index: 0;
    }

    .org-area::before {
        left: 0;
        border-radius: 0 50% 50% 0;
    }

    .org-area::after {
        right: 0;
        border-radius: 50% 0 0 50%;
    }

    .org-area .container {
        position: relative;
        z-index: 1;
    }

    .org-area .title-default-center {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 800;
        margin-bottom: 60px;
        position: relative;
        display: inline-block;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.02em;
        text-align: center;
        width: 100%;
    }

    .org-area .title-default-center::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 6px;
        background: var(--accent-gradient);
        border-radius: 3px;
        box-shadow: 0 0 20px var(--glow-color);
    }
    
    .org-area .subtitle {
        text-align: center;
        color: var(--text-secondary);
        margin-top: -40px;
        margin-bottom: 60px;
        font-size: 1.2rem;
    }

    /* Container with side decorations */
    .org-card-container {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
    }

    /* Side decoration boxes */
    .side-box {
        width: 80px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        flex-shrink: 0;
    }

    .side-element {
        width: 80px;
        height: 80px;
        background: var(--card-bg);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-light);
        border: 2px solid rgba(0, 127, 95, 0.1);
        transition: var(--transition-smooth);
    }

    .side-element:hover {
        transform: scale(1.1);
        border-color: var(--light-green);
        box-shadow: var(--shadow-heavy);
    }

    .side-element i {
        font-size: 2rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Main Org Card */
    .org-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-light);
        transition: var(--transition-smooth);
        text-align: center;
        position: relative;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 10px;
        flex: 1;
        max-width: 900px;
    }

    .org-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-heavy);
    }

    .org-card img {
        width: 100%;
        height: auto;
        border-radius: 12px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        color: var(--text-secondary);
        background: var(--card-bg);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-light);
        margin-top: 2rem;
    }

    .empty-state .empty-icon i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: var(--light-green);
        opacity: 0.6;
    }

    .empty-state h3 {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 2rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        font-family: 'Inter', sans-serif;
        font-size: 1.1rem;
    }

    .empty-state .btn-primary {
        background: var(--primary-gradient);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        transition: var(--transition-smooth);
        text-decoration: none;
        display: inline-block;
    }
    
    .empty-state .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .side-box {
            width: 60px;
        }
        
        .side-element {
            width: 60px;
            height: 60px;
        }
        
        .side-element i {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 992px) {
        .side-box {
            display: none;
        }
        
        .org-area::before,
        .org-area::after {
            width: 100px;
        }
    }

    @media (max-width: 768px) {
        .hero-profile {
            height: 300px;
        }

        .hero-profile .main-title {
            font-size: 2.5rem;
        }

        .hero-profile .sub-title {
            font-size: 1.2rem;
        }

        .org-area {
            padding: 60px 0;
        }
        
        .org-area::before,
        .org-area::after {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<section class="hero-profile">
    <div class="hero-content">
        <h1 class="main-title">Struktur Organisasi</h1>
        <p class="sub-title">Mengenal tim kepemimpinan dan pengurus yang memajukan organisasi</p>
    </div>
</section>

<section class="org-area">
    <div class="container">
        <h2 class="title-default-center">Bagan Struktur</h2>
        <p class="subtitle">Struktur organisasi dan tata kelola kami.</p>
        
        @if(isset($strukturOrganisasi) && $strukturOrganisasi->foto)
            <div class="org-card-container">
                <!-- Left Side Decoration -->
                <div class="side-box">
                    <div class="side-element">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="side-element">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="side-element">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="org-card">
                    <img src="{{ asset('storage/' . $strukturOrganisasi->foto) }}" 
                         alt="Struktur Organisasi" 
                         class="img-fluid">
                </div>

                <!-- Right Side Decoration -->
                <div class="side-box">
                    <div class="side-element">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="side-element">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="side-element">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-sitemap"></i>
                </div>
                <h3>Belum Ada Data Struktur</h3>
                <p>Gambar struktur organisasi akan ditampilkan di sini setelah tersedia.</p>
                <a href="{{ url('/') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali ke Beranda
                </a>
            </div>
        @endif
    </div>
</section>
@endsection