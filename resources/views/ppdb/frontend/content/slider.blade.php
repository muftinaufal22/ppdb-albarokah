{{-- Import Font --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

<style>
    :root {
        --ppdb-dark-green: #014A36;
        --ppdb-light-green: #25ff51ff;
        --ppdb-font-heading: 'Playfair Display', serif;
        --ppdb-font-body: 'Montserrat', sans-serif;
    }
    .ppdb-hero-section {
        position: relative;
        height: 500px; /* LEBIH TINGGI */
        overflow: hidden;
        background-image: url('{{ asset('Assets/Frontend/img/slider/foto_login.png') }}');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        padding: 0 10%;
    }
    .ppdb-hero-section::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.45);
        z-index: 1;
    }
    .ppdb-hero-content {
        position: relative;
        z-index: 2;
        background-color: rgba(1, 74, 54, 0.85);
        padding: 2.5rem 4rem; /* LEBIH BESAR */
        clip-path: polygon(0 0, 100% 0, 95% 100%, 0% 100%);
        max-width: 60%;
        color: white;
        animation: slideInFromLeft 1s ease-out 0.2s backwards;
    }
    .ppdb-hero-content h1 {
        font-family: var(--ppdb-font-heading);
        font-size: 3.8rem; /* LEBIH BESAR */
        margin: 0;
        color: white;
    }
    .ppdb-hero-content p {
        font-family: var(--ppdb-font-body);
        font-size: 1.3rem; /* LEBIH BESAR */
        margin-top: 0.5rem;
        color: white;
        opacity: 0.9;
    }
    .ppdb-hero-button {
        background-color: var(--ppdb-light-green);
        color: var(--ppdb-dark-green);
        padding: 14px 32px; /* LEBIH BESAR */
        border-radius: 5px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.1rem; /* LEBIH BESAR */
        text-transform: uppercase;
        margin-top: 2rem;
        display: inline-block;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(37, 255, 81, 0.3);
    }
    .ppdb-hero-button:hover {
        background-color: #ffffff;
        transform: scale(1.05) translateY(-3px);
        box-shadow: 0 8px 25px rgba(37, 255, 81, 0.5);
    }

    @keyframes slideInFromLeft {
        from { transform: translateX(-50px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>

<header class="ppdb-hero-section">
    <div class="ppdb-hero-content">
        <h1>Pendaftaran Dibuka!</h1>
        <p>Bergabunglah dengan RA Al Barokah untuk pendidikan usia dini yang Islami dan berkualitas.</p>
        <a href="{{route('register')}}" class="ppdb-hero-button">Daftar Sekarang</a>
    </div>
</header>