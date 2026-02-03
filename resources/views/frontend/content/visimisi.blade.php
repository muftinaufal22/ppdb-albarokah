@extends('layouts.Frontend.app')

@section('title')
    Sejarah RA Al Barokah
@endsection

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sejarah - RA Al Barokah</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Global Variables */
        :root {
            --primary-gradient: linear-gradient(135deg, #014A36 0%, #007F5F 100%);
            --secondary-gradient: linear-gradient(135deg, #a8e063 0%, #56ab2f 100%);
            --accent-gradient: linear-gradient(135deg, #00C9A7 0%, #00E0A6 100%);
            --text-primary: #2d3748;
            --text-heading: #014A36;
            --text-secondary: #4a5568;
            --shadow-light: 0 10px 40px rgba(0, 0, 0, 0.1);
            --border-radius: 30px;
            --transition-smooth: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            --glow-color: rgba(1, 74, 54, 0.4);
        }

        /* Hero Section Styling */
        .hero-profile {
            width: 100%; height: 400px; background-image: url('{{ asset('storage/images/slider/testimoni.png') }}');
            background-position: center; background-size: cover; display: flex; flex-direction: column;
            align-items: center; justify-content: center; text-align: center; color: #fff;
            position: relative; padding: 20px;
        }
        .hero-profile::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.55); z-index: 1;
        }
        .hero-profile .hero-content { position: relative; z-index: 2; }
        .hero-profile .main-title {
            font-family: 'Playfair Display', serif; font-size: clamp(3rem, 8vw, 4.5rem); font-weight: 700;
            margin-bottom: 15px; line-height: 1.2; color: #fff; text-shadow: 2px 2px 10px rgba(0,0,0,0.7);
        }
        .hero-profile .sub-title {
            font-family: 'Roboto', sans-serif; font-size: clamp(1.2rem, 4vw, 1.6rem); font-weight: 400;
            opacity: 0.95; color: #fff; text-shadow: 1px 1px 7px rgba(0,0,0,0.6);
        }

        /* Main Content Area Styling */
        .sejarah-area {
            position: relative; background: linear-gradient(135deg, #f8fafc 0%, #eef5ea 100%);
            padding: 100px 0; overflow: hidden;
        }
        .sejarah-area::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 15% 25%, rgba(168, 224, 99, 0.1) 0%, transparent 40%),
                        radial-gradient(circle at 85% 75%, rgba(86, 171, 47, 0.1) 0%, transparent 40%);
            animation: backgroundFloat 25s ease-in-out infinite; z-index: 0;
        }
        @keyframes backgroundFloat {
            0%, 100% { background-position: 0% 0%; } 50% { background-position: 10% -10%; }
        }
        .sejarah-area .container { position: relative; z-index: 1; }
        
        /* Title Styling */
        .title-default-left {
            font-size: clamp(2.8rem, 5vw, 4rem);
            font-weight: 800; margin-bottom: 80px;
            position: relative; display: inline-block; background: var(--primary-gradient);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
            letter-spacing: -0.02em;
        }
        .title-default-left::after {
            content: ''; position: absolute; bottom: -20px; left: 0; width: 100px; height: 8px;
            background: var(--accent-gradient); border-radius: 4px; box-shadow: 0 0 20px var(--glow-color);
        }

        /* Sejarah Content Layout */
        .sejarah-container {
            display: flex; flex-wrap: wrap; gap: 60px;
            align-items: center;
            background: none; box-shadow: none; padding: 0;
        }
        .sejarah-content { 
            flex: 1 1 45%;
            min-width: 300px; 
        }
        .sejarah-image {
            flex: 1 1 55%;
            min-width: 300px; 
            border-radius: var(--border-radius);
            box-shadow: 0 20px 60px rgba(1, 74, 54, 0.25);
            animation: floatImage 5s ease-in-out infinite;
        }
        .timeline { list-style: none; padding: 0; position: relative; }
        .timeline::before {
            content: ''; position: absolute; top: 24px; left: 24px; bottom: 24px;
            width: 8px;
            background: #e2e8f0; border-radius: 4px;
        }
        .timeline-item { position: relative; padding-left: 80px; margin-bottom: 60px; }
        .timeline-item:last-child { margin-bottom: 0; }
        .timeline-icon {
            position: absolute; left: 0; top: 0;
            width: 52px; height: 52px;
            background: var(--primary-gradient); color: #fff;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            border: 4px solid #fff; box-shadow: 0 5px 25px var(--glow-color);
            animation: iconPulse 2.5s infinite ease-in-out;
        }

        .timeline-item h4 {
            font-size: 2.25rem;
            color: var(--text-heading);
            margin-bottom: 15px; font-weight: 700;
        }
        
        .timeline-item p {
            /* --- UKURAN FONT PARAGRAF DIPERBESAR LAGI --- */
            font-size: 1.4rem; 
            color: var(--text-secondary); line-height: 1.8;
            margin-bottom: 0; text-align: justify;
        }
        
        .sejarah-image img {
            width: 100%; height: 100%; object-fit: cover;
            display: block; border-radius: var(--border-radius);
        }

        /* Keyframes untuk Animasi */
        @keyframes iconPulse {
            0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); }
        }
        @keyframes floatImage {
            0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); }
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sejarah-container { flex-direction: column-reverse; gap: 50px; }
            .sejarah-content, .sejarah-image { flex-basis: 100%; }
            .timeline-item { padding-left: 70px; }
        }
        @media (max-width: 768px) {
            .hero-profile { height: 300px; }
            .hero-profile .main-title { font-size: 2.5rem; }
            .hero-profile .sub-title { font-size: 1.2rem; }
            .sejarah-area { padding: 60px 20px; }
            .title-default-left { font-size: 2.5rem; margin-bottom: 60px; }
            .timeline-item h4 { font-size: 1.8rem; }
            .timeline-item p { 
                font-size: 1.25rem;  /* --- Ukuran mobile juga disesuaikan --- */
            }
        }
    </style>
</head>
<body>

    <div class="hero-profile">
        <div class="hero-content">
            <h1 class="main-title">Sejarah RA Al Barokah</h1>
            <p class="sub-title">Perjalanan dan Dedikasi Kami dalam Mendidik Generasi Bangsa</p>
        </div>
    </div>

    <div class="sejarah-area">
        <div class="container">
            <h2 class="title-default-left">Awal Mula & Perkembangan</h2>

            <div class="sejarah-container">
                <div class="sejarah-content">
                    
                    <ul class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <h4>Cikal Bakal & Pendirian</h4>
                            <p>
                                RA Al Barokah didirikan pada tahun 2011 atas dasar keprihatinan dan semangat untuk memberikan pendidikan anak usia dini yang berkualitas dengan landasan nilai-nilai Islam. Berawal dari sebuah bangunan sederhana dengan segelintir murid, para pendiri bekerja keras dengan penuh dedikasi untuk menciptakan lingkungan belajar yang aman, menyenangkan, dan mendidik.
                            </p>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <h4>Era Perkembangan</h4>
                            <p>
                                Dengan dukungan penuh dari masyarakat sekitar, sekolah ini terus berkembang dari tahun ke tahun. Kami melakukan berbagai inovasi kurikulum, menambah fasilitas bermain dan belajar, serta meningkatkan kualitas tenaga pengajar secara berkelanjutan. Setiap langkah kami selalu berlandaskan komitmen untuk membentuk generasi cerdas berakhlak mulia.
                            </p>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h4>Masa Kini & Masa Depan</h4>
                            <p>
                                Kini, RA Al Barokah telah menjadi salah satu lembaga pendidikan prasekolah yang dipercaya oleh ratusan orang tua. Kami bangga telah meluluskan banyak alumni yang tumbuh menjadi pribadi yang berprestasi dan berkontribusi positif bagi lingkungannya, dan kami akan terus berinovasi untuk masa depan.
                            </p>
                        </li>
                    </ul>

                </div>
                
                <div class="sejarah-image">
                    {{-- Pastikan path gambar ini benar dan ada filenya --}}
                    <img src="{{ asset('Assets/Frontend/img/sejarah.png') }}" alt="Foto Sejarah RA Al Barokah">
                </div>
            </div>
        </div>
    </div>

</body>
</html>

@endsection