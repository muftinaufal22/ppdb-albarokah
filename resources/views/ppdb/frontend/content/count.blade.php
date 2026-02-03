{{-- Import Font --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

<style>
    :root {
        --ppdb-dark-green: #014A36;
        --ppdb-font-heading: 'Playfair Display', serif;
        --ppdb-font-body: 'Montserrat', sans-serif;
    }
    .ppdb-section-persyaratan {
        /* BATASAN: Latar belakang sendiri */
        background: linear-gradient(180deg, #f0f5f3 0%, #ffffff 100%);
        padding: 80px 10%; /* LEBIH BESAR */
        position: relative;
        z-index: 1;
        font-family: var(--ppdb-font-body);
        overflow: hidden; /* Penting untuk blob */
    }

    /* ANIMASI BLOB (dari beranda Anda) */
    .ppdb-background-blob {
        position: absolute;
        top: -15%;
        left: -20%;
        width: 140%;
        height: 120%;
        z-index: -1;
        opacity: 0.15; /* Dibuat lebih halus */
    }

    .ppdb-requirements-card {
        background-color: #ffffff;
        padding: 3.5rem; /* LEBIH BESAR */
        border-radius: 12px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        border-top: 6px solid var(--ppdb-dark-green);
        position: relative; /* Untuk z-index di atas blob */
        z-index: 2;
    }
    .ppdb-requirements-card h2 {
        font-family: var(--ppdb-font-heading);
        font-size: 3rem; /* LEBIH BESAR */
        color: var(--ppdb-dark-green);
        margin-top: 0;
        margin-bottom: 1rem;
        text-align: center;
    }
    .ppdb-requirements-card h3 {
        font-family: var(--ppdb-font-body);
        font-weight: 600;
        text-align: center;
        color: #333;
        font-size: 1.4rem; /* LEBIH BESAR */
        margin-bottom: 3rem;
    }
    .ppdb-requirements-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); /* LEBIH BESAR */
        gap: 1.8rem; /* LEBIH BESAR */
        counter-reset: item-counter;
    }
    .ppdb-requirements-card li {
        font-weight: 600;
        position: relative;
        line-height: 1.7; /* LEBIH BESAR */
        font-size: 1.1rem; /* LEBIH BESAR */
        color: #000;
        background: #f8f9fa;
        padding: 1.2rem 1.2rem 1.2rem 4rem; /* LEBIH BESAR */
        border-radius: 8px;
        border-left: 4px solid var(--ppdb-dark-green);
        transition: all 0.3s ease;
        counter-increment: item-counter;
    }
    .ppdb-requirements-card li:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 8px 20px rgba(0,0,0,0.07);
    }
    .ppdb-requirements-card li::before {
        content: counter(item-counter);
        position: absolute;
        left: 1.2rem;
        top: 50%;
        transform: translateY(-50%);
        background-color: var(--ppdb-dark-green);
        color: white;
        width: 35px; /* LEBIH BESAR */
        height: 35px; /* LEBIH BESAR */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
    }
    .ppdb-requirements-card .note {
        margin-top: 3rem;
        text-align: center;
        font-style: italic;
        color: #555;
        background-color: #fffaf0;
        padding: 1.2rem;
        border-radius: 8px;
        border-left: 5px solid #f59e0b;
        font-size: 1.1rem; /* LEBIH BESAR */
    }
</style>

<section class="ppdb-section-persyaratan">
    
    {{-- ANIMASI BLOB (dari beranda Anda) --}}
    <div class="ppdb-background-blob">
        <svg viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg" width="100%">
            <path fill="#014A36">
                <animate attributeName="d" dur="15s" repeatCount="indefinite" values="M440.5,320.5Q423,401,336.5,431Q250,461,168.5,433.5Q87,406,62,328Q37,250,65,173.5Q93,97,171.5,62Q250,27,330.5,63Q411,99,444.5,174.5Q478,250,440.5,320.5Z;
                M451,323Q436,406,343,431Q250,456,174,432.5Q98,409,55,329.5Q12,250,55,170.5Q98,91,174,67Q250,43,338,68Q426,93,451,171.5Q476,250,451,323Z;
                M426,316.5Q398,393,324,411Q250,429,174,420Q98,411,59.5,330.5Q21,250,58,172Q95,94,172.5,60Q250,26,327,51.5Q404,77,426,163.5Q448,250,426,316.5Z;
                M440.5,320.5Q423,401,336.5,431Q250,461,168.5,433.5Q87,406,62,328Q37,250,65,173.5Q93,97,171.5,62Q250,27,330.5,63Q411,99,444.5,174.5Q478,250,440.5,320.5Z;"></animate>
            </path>
        </svg>
    </div>

    <div class="ppdb-requirements-card animate-on-scroll">
        <h2>PERSYARATAN UMUM (PPDB)</h2>
        <h3>RA AL BAROKAH Temuasri Tahun Pelajaran 2025 / 2026</h3>
        
        <ul>
            <li>Usia kurang lebih 4 tahun.</li>
            <li>Sehat jasmani dan rohani.</li>
            <li>Biaya pendaftaran sebesar Rp 100.000,-</li>
            <li>Mengisi Formulir</li>
            <li>Mengirim foto akta kelahiran.</li>
            <li>Mengirim pas foto berwarna ukuran 3 x 4.</li>
            <li>Mengirim foto Kartu Keluarga.</li>
            <li>Mengirim foto KTP Orangtua.</li>
            <li>Pembayaran dapat dilakukan setelah murid diterima.</li> 
        </ul>

        <p class="note">*Pastikan Anda sudah menyiapkan semua persyaratan di atas untuk kelancaran proses pendaftaran.</p>
    </div>
</section>