<header id="main-header" class="header-area">
    <div class="container">
        <div class="header-inner">

            <div class="header-logo">
                <a href="/" style="display: flex; align-items: center; text-decoration: none;">
                    @if (@$footer->logo == null)
                        <img class="logo-img" src="{{ asset(path: 'Assets/Frontend/img/foto_logo.png') }}" alt="logo">
                    @else
                        <img class="logo-img" src="{{ asset('storage/images/logo/' . $footer->logo) }}" alt="logo">
                    @endif
                    <div class="logo-text">
                        <h2>RA AL BAROKAH</h2>
                    </div>
                </a>
            </div>

            <div class="header-nav-wrapper">
                <nav id="desktop-nav">
                    <ul>
                        <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="/">BERANDA</a></li>
                        <li><a href="#">PROFILE</a>
                            <ul>
                                <li><a href="{{ route('profile.sekolah') }}">Profil GURU</a></li>
                                <li><a href="{{ route('visimisi.sekolah') }}">SEJARAH</a></li>
                            </ul>
                        </li>
                       <li><a href="#">INFORMASI SEKOLAH</a>
    <ul>
        <li><a href="{{ route('frontend.prestasi') }}">Prestasi</a></li>
        <li><a href="{{ route('frontend.struktur') }}">Struktur Organisasi</a></li>
    </ul>
</li>

                        <li class="{{ request()->is('berita') ? 'active' : '' }}"><a href="{{ route('berita') }}">BERITA</a></li>
                        <li class="{{ request()->is('ppdb') ? 'active' : '' }}">
                            <a href="{{ url('ppdb') }}">PPDB</a>
                        </li>
                        <!-- <li><a href="#">LAINNYA</a></li>
                    </ul> -->
                </nav>

                <div class="header-auth">
                    @auth
                        <a href="/home" class="apply-now-btn2">Home</a>
                    @else
                        <a class="apply-now-btn2" href="{{ route('login') }}"> Masuk</a>
                    @endauth
                </div>
            </div>
            
        </div>
    </div>
</header>

<style>
    /* 1. PENGATURAN DASAR HEADER */
    .header-area {
        position: fixed; /* Membuat header menempel di atas */
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
        background-color: transparent; /* Awalnya transparan */
        padding: 15px 0;
        transition: background-color 0.4s ease, padding 0.4s ease, box-shadow 0.4s ease;
    }

    /* 2. STYLE HEADER SAAT DI-SCROLL */
    .header-area.scrolled {
        background-color: #014A36; /* Warna hijau solid saat di-scroll */
        padding: 10px 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* 3. LAYOUT INTERNAL HEADER */
    .header-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* 4. LOGO DAN TEKS */
    .header-logo .logo-img {
        max-height: 55px; /* Sesuaikan ukuran logo */
        width: auto;
        transition: max-height 0.4s ease;
        margin-right: 15px;
    }
    .header-area.scrolled .header-logo .logo-img {
        max-height: 50px; /* Logo sedikit mengecil saat di-scroll */
    }
    .logo-text h2 {
        margin: 0;
        font-size: 22px;
        font-weight: bold;
        color: #fff; /* Teks putih agar terlihat di background transparan/gelap */
        text-shadow: 1px 1px 3px rgba(0,0,0,0.5); /* Bayangan agar teks lebih jelas */
    }

    /* 5. WRAPPER UNTUK NAVIGASI DAN TOMBOL */
    .header-nav-wrapper {
        display: flex;
        align-items: center;
        gap: 20px; /* Jarak antara menu navigasi dan tombol 'Masuk' */
    }

    /* 6. TOMBOL MASUK/HOME */
    .apply-now-btn2 {
        background: transparent !important; /* Awalnya transparan */
        color: #fff !important;
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: bold;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid #fff; /* Border putih */
        display: inline-block;
        white-space: nowrap;
    }
    .apply-now-btn2:hover {
        background: #fff !important;
        color: #014A36 !important;
        transform: translateY(-2px);
    }
    .header-area.scrolled .apply-now-btn2 {
        background: #fff !important;
        color: #014A36 !important;
        border-color: #fff;
    }
    .header-area.scrolled .apply-now-btn2:hover {
        background: transparent !important;
        color: #fff !important;
        border-color: #fff;
    }


    /* 7. STYLE MENU NAVIGASI (Mengambil dari style asli Anda) */
    #desktop-nav > ul > li > a {
        font-weight: bold;
        text-transform: uppercase;
        font-size: 14px;
        color: #fff !important;
        padding: 15px 18px;
        transition: all 0.3s ease;
        border-radius: 5px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.4);
    }
    #desktop-nav > ul > li:hover > a,
    #desktop-nav > ul > li.active > a {
        background: rgba(255, 255, 255, 0.1);
    }

    /* 8. STYLE DROPDOWN (Sama seperti style asli Anda) */
    #desktop-nav ul li ul {
        background: #014A36 !important;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        position: absolute;
        top: 100%;
        left: 0;
        width: 220px;
        padding: 5px 0;
        border-radius: 0 0 8px 8px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s ease;
    }
    #desktop-nav ul > li:hover > ul {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    #desktop-nav ul li ul li a {
        color: #fff !important;
        padding: 12px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }
    #desktop-nav ul li ul li a:hover {
        background: rgba(255, 255, 255, 0.1);
        padding-left: 25px;
    }
    .thired-level {
        background: #013324 !important;
        position: absolute;
        top: 0;
        left: 100%;
    }
</style>

<script>
    // Script sederhana untuk menambahkan class 'scrolled' pada header saat halaman di-scroll
    window.addEventListener('scroll', function() {
        const header = document.getElementById('main-header');
        
        // Tambahkan class 'scrolled' jika scroll lebih dari 50px, jika tidak, hapus class-nya
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
</script>