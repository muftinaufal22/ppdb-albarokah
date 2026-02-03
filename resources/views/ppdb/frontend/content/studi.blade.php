{{-- Import Font --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

<style>
    :root {
        --ppdb-dark-green: #014A36;
        --ppdb-font-heading: 'Playfair Display', serif;
        --ppdb-font-body: 'Montserrat', sans-serif;
    }
    .ppdb-section-studi {
        /* BATASAN: Latar belakang putih polos */
        background: #ffffff;
        padding: 80px 10%; /* LEBIH BESAR */
        position: relative;
        z-index: 1;
        font-family: var(--ppdb-font-body);
        text-align: center;
    }
    /* Efek Kaca (Glassmorphism) yang diperbesar */
    .ppdb-flow-card-glass {
        position: relative;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        padding: 3rem; /* LEBIH BESAR */
        border-radius: 20px;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.1);
        display: inline-block;
        transition: all 0.4s ease;
    }
    .ppdb-flow-card-glass:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.15);
    }
    .ppdb-flow-card-glass h2 {
        font-family: var(--ppdb-font-heading);
        font-size: 3rem; /* LEBIH BESAR */
        color: var(--ppdb-dark-green);
        margin-top: 0;
        margin-bottom: 2.5rem;
    }
    .ppdb-flow-card-glass img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
</style>

<section class="ppdb-section-studi">
    {{-- Ini akan dianimasikan oleh script dari index.blade.php --}}
    <div class="ppdb-flow-card-glass animate-on-scroll">
        <h2>Alur Pendaftaran</h2>
        <img src="{{asset('Assets/Frontend/img/banner/alur.png')}}" 
             alt="Alur Pendaftaran" 
             class="img-fluid d-block mx-auto">
    </div>
</section>