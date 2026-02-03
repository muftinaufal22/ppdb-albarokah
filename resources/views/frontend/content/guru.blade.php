<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guru dan Tenaga Kerja - RA Al Barokah</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <style>
        /* Global Variables and Dark Mode */
        :root {
            --primary-gradient: linear-gradient(135deg, #014A36 0%, #007F5F 100%);
            --secondary-gradient: linear-gradient(135deg, #a8e063 0%, #56ab2f 100%); /* Changed to light green */
            --accent-gradient: linear-gradient(135deg, #00C9A7 0%, #00E0A6 100%);
            --text-primary: #2d3748;
            --text-secondary: #718096;
            --card-bg: rgba(255, 255, 255, 0.95);
            --overlay-bg: linear-gradient(135deg, rgba(1, 74, 54, 0.95), rgba(0, 127, 95, 0.95));
            --shadow-light: 0 10px 40px rgba(0, 0, 0, 0.1);
            --shadow-heavy: 0 20px 60px rgba(0, 0, 0, 0.2);
            --border-radius: 24px;
            --transition-smooth: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            --glow-color: rgba(1, 74, 54, 0.4);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --text-primary: #e2e8f0;
                --text-secondary: #a0aec0;
                --card-bg: rgba(45, 55, 72, 0.95);
            }
        }

        /* Hero Section Styling */
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
            font-size: clamp(4.5rem, 10vw, 6rem);
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.2;
            color: #fff;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.7);
            animation: fadeInDown 1.5s forwards;
        }

        .hero-profile .sub-title {
            font-family: 'Roboto', sans-serif;
            font-size: clamp(1.5rem, 5vw, 2.5rem);
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

        /* Main Content Area */
        .lecturers-area {
            position: relative;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 80px 0;
            overflow: hidden;
        }

        .lecturers-area::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            /* Changed to light green gradient */
            background: radial-gradient(circle at 25% 25%, rgba(168, 224, 99, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 75% 75%, rgba(86, 171, 47, 0.15) 0%, transparent 50%);
            animation: backgroundFloat 20s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes backgroundFloat {
            0%, 100% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.1); }
        }

        .lecturers-area::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Changed to light green gradient */
            background-image: radial-gradient(2px 2px at 20px 30px, rgba(255, 255, 255, 0.3), transparent),
                              radial-gradient(2px 2px at 40px 70px, rgba(86, 171, 47, 0.3), transparent),
                              radial-gradient(1px 1px at 90px 40px, rgba(168, 224, 99, 0.3), transparent);
            background-repeat: repeat;
            background-size: 150px 100px;
            animation: particleFloat 25s linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes particleFloat {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-100px); }
        }

        .lecturers-area .container {
            position: relative;
            z-index: 1;
        }

        .lecturers-area .title-default-left {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 60px;
            position: relative;
            display: inline-block;
            background: linear-gradient(135deg, #014A36 0%, #007F5F 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
        }

        .lecturers-area .title-default-left::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 0;
            width: 80px;
            height: 6px;
            background: var(--accent-gradient);
            border-radius: 3px;
            box-shadow: 0 0 20px var(--glow-color);
        }

        .lecturers-area .title-default-left::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -30px;
            width: 100px;
            height: 100px;
            background: var(--secondary-gradient); /* Now using the light green secondary gradient */
            border-radius: 50%;
            opacity: 0.15;
            animation: titleGlow 3s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            0% { transform: scale(0.8) rotate(0deg); opacity: 0.15; }
            100% { transform: scale(1.2) rotate(360deg); opacity: 0.3; }
        }

        /* Enhanced Card Design */
        .lecturer-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: var(--transition-smooth);
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform-style: preserve-3d;
        }

        .lecturer-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--primary-gradient);
            border-radius: var(--border-radius);
            opacity: 0;
            transition: var(--transition-smooth);
            z-index: -1;
            transform: scale(1.05);
            filter: blur(20px);
        }

        .lecturer-card:hover::before {
            opacity: 0.3;
        }

        .lecturer-card:hover {
            transform: translateY(-20px) rotateX(5deg) rotateY(5deg);
            box-shadow: var(--shadow-heavy), 0 0 40px var(--glow-color);
            border-color: rgba(1, 74, 54, 0.3);
        }

        /* Menghilangkan overlay yang tidak lagi digunakan */
        .lecturer-card .lecturer-social-overlay {
            display: none;
        }

        /* Content Styling Baru */
        .lecturer-card .lecturer-content {
            padding: 25px;
            text-align: center;
        }

        .lecturer-card .lecturer-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 5px;
            text-transform: capitalize;
        }

        .lecturer-card .lecturer-designation {
            font-size: 1rem;
            color: var(--text-secondary);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .lecturer-card .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .lecturer-card .social-links a {
            color: var(--text-secondary);
            font-size: 20px;
            transition: color 0.3s ease, transform 0.3s ease;
        }
        
        .lecturer-card .social-links a:hover {
            color: #014A36;
            transform: translateY(-5px);
        }

        /* Responsive Design */
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

            .lecturers-area {
                padding: 60px 0;
            }
            
            .lecturers-area .title-default-left {
                font-size: 2.5rem;
                margin-bottom: 40px;
            }
            
            .lecturer-card .lecturer-img-container {
                height: 280px;
            }
            
            .lecturer-card .lecturer-content {
                padding: 20px;
            }
        }

        /* Carousel Navigation Enhancement */
        .rc-carousel .owl-nav button {
            background: var(--primary-gradient) !important;
            border: none !important;
            border-radius: 50% !important;
            width: 60px !important;
            height: 60px !important;
            color: white !important;
            font-size: 20px !important;
            transition: var(--transition-smooth) !important;
            box-shadow: var(--shadow-light) !important;
        }

        .rc-carousel .owl-nav button:hover {
            transform: scale(1.1) !important;
            box-shadow: var(--shadow-heavy) !important;
        }

        .rc-carousel .owl-nav button.owl-prev {
            left: -30px !important;
        }

        .rc-carousel .owl-nav button.owl-next {
            right: -30px !important;
        }
    </style>
</head>
<body>

    <div class="hero-profile">
        <div class="hero-content">
            <h1 class="main-title">Guru dan Tenaga Kerja</h1>
            <p class="sub-title">Nama-Nama Guru dan Tenaga Kerja RA Al Barokah</p>
        </div>
    </div>

    <div class="lecturers-area py-5">
        <div class="container">
            <h2 class="title-default-left">Guru dan Tenaga Kerja</h2>
        </div>
        <div class="container">
            <div class="rc-carousel" data-loop="true" data-items="4" data-margin="30" data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="false" data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true" data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true" data-r-x-medium-dots="false" data-r-small="3" data-r-small-nav="true" data-r-small-dots="false" data-r-medium="4" data-r-medium-nav="true" data-r-medium-dots="false" data-r-large="4" data-r-large-nav="true" data-r-large-dots="false">
                
                @foreach ($pengajar as $pengajars)
                    <div class="lecturer-card">
                        <div class="lecturer-img-container">
                            <img class="img-fluid" src="{{ asset('storage/images/profile/' . $pengajars->foto_profile) }}" alt="Foto {{ $pengajars->name }}">
                        </div>
                        <div class="lecturer-content">
                            <h3 class="lecturer-name">{{ $pengajars->name }}</h3>
                            <p class="lecturer-designation">{{ $pengajars->userDetail->mengajar }}</p>
                            
                            {{-- Bagian baru untuk link media sosial di bawah foto --}}
                            <div class="social-links">
                                @if($pengajars->userDetail->website)
                                    <a href="{{ $pengajars->userDetail->website }}" target="_blank" title="Website"><i class="fa fa-globe"></i></a>
                                @endif
                                @if($pengajars->email)
                                    <a href="mailto:{{ $pengajars->email }}" title="Email"><i class="fa fa-envelope-o"></i></a>
                                @endif
                                @if($pengajars->userDetail->linkedin)
                                    <a href="{{ 'https://www.linkedin.com/in/' . $pengajars->userDetail->linkedin }}" target="_blank" title="LinkedIn"><i class="fa fa-linkedin"></i></a>
                                @endif
                                @if($pengajars->userDetail->twitter)
                                    <a href="{{ 'https://www.twitter.com/' . $pengajars->userDetail->twitter }}" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a>
                                @endif
                                @if($pengajars->userDetail->facebook)
                                    <a href="{{ 'https://www.facebook.com/' . $pengajars->userDetail->facebook }}" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a>
                                @endif
                                @if($pengajars->userDetail->instagram)
                                    <a href="{{ 'https://www.instagram.com/' . $pengajars->userDetail->instagram }}" target="_blank" title="Instagram"><i class="fa fa-instagram"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach 
                
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        $(document).ready(function(){
            $('.rc-carousel').owlCarousel({
                loop: true,
                margin: 30,
                autoplay: false,
                autoplayTimeout: 10000,
                smartSpeed: 2000,
                dots: false,
                nav: true,
                navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
                responsive: {
                    0: {
                        items: 1,
                        nav: true,
                        dots: false
                    },
                    576: {
                        items: 2,
                        nav: true,
                        dots: false
                    },
                    768: {
                        items: 3,
                        nav: true,
                        dots: false
                    },
                    992: {
                        items: 4,
                        nav: true,
                        dots: false
                    }
                }
            });
        });
    </script>

</body>
</html>