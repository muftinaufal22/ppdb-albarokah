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
    .ppdb-why-section {
        /* BATASAN: Latar belakang Parallax (dari beranda) */
        background-image: url('{{ asset('storage/images/slider/testimoni.png') }}');
        background-attachment: fixed;
        background-size: cover;
        background-position: center;
        padding: 80px 10%; /* LEBIH BESAR */
        position: relative;
        font-family: var(--ppdb-font-body);
    }
    .ppdb-why-section .title-box {
        display: block;
        background-color: rgba(255, 255, 255, 0.95);
        padding: 20px 35px; /* LEBIH BESAR */
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin: 0 auto 3.5rem auto; /* LEBIH BESAR */
        border: 4px solid var(--ppdb-dark-green); /* LEBIH TEBAL */
        text-align: center;
        max-width: 700px;
    }
    .ppdb-why-section h2 {
        font-family: var(--ppdb-font-heading);
        font-size: 3rem; /* LEBIH BESAR */
        margin: 0;
        line-height: 1.3;
        color: var(--ppdb-dark-green);
    }
    .ppdb-faq-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.8rem; /* LEBIH BESAR */
    }
    .ppdb-faq-item {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .ppdb-faq-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    }
    .ppdb-faq-header {
        padding: 1.5rem; /* LEBIH BESAR */
        cursor: pointer;
        font-weight: 700;
        font-size: 1.2rem; /* LEBIH BESAR */
        color: var(--ppdb-dark-green);
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.3s, color 0.3s;
    }
    .ppdb-faq-header .faq-title-content {
        display: flex;
        align-items: center;
    }
    .ppdb-faq-header .faq-count {
        background-color: var(--ppdb-dark-green);
        color: white;
        border-radius: 50%;
        width: 32px; /* LEBIH BESAR */
        height: 32px; /* LEBIH BESAR */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
        transition: all 0.3s;
        font-size: 1rem;
    }
    .ppdb-faq-header .faq-toggle-icon {
        font-weight: bold;
        font-size: 1.8rem; /* LEBIH BESAR */
        transition: transform 0.3s ease-out;
    }
    .ppdb-faq-header.active {
        background-color: var(--ppdb-dark-green);
        color: white;
    }
    .ppdb-faq-header.active .faq-count {
        background-color: white;
        color: var(--ppdb-dark-green);
    }
    .ppdb-faq-header.active .faq-toggle-icon {
        transform: rotate(45deg);
    }
    .ppdb-faq-body {
        padding: 1.5rem 1.8rem; /* LEBIH BESAR */
        line-height: 1.8; /* LEBIH BESAR */
        color: #333;
        background: #fdfdfd;
        display: none;
        font-size: 1.05rem; /* LEBIH BESAR */
    }
</style>

<section class="ppdb-why-section">
    <div class="title-box animate-on-scroll">
        <h2>Kenapa Harus RA Al Barokah?</h2>
    </div>
    
    <div class="ppdb-faq-grid animate-on-scroll">
        {{-- Item 1 --}}
        <div class="ppdb-faq-item">
            <div class="ppdb-faq-header" data-target="#faq1">
                <div class="faq-title-content">
                    <span class="faq-count">1</span>
                    <span>Terakreditasi A</span>
                </div>
                <span class="faq-toggle-icon">+</span>
            </div>
            <div class="ppdb-faq-body" id="faq1">
                <p>RA Al Barokah adalah . (Deskripsi Anda di sini)</p>
            </div>
        </div>
        {{-- Item 2 --}}
        <div class="ppdb-faq-item">
            <div class="ppdb-faq-header" data-target="#faq2">
                <div class="faq-title-content">
                    <span class="faq-count">2</span>
                    <span>Memiliki Fasilitas Yang Lengkap</span>
                </div>
                <span class="faq-toggle-icon">+</span>
            </div>
            <div class="ppdb-faq-body" id="faq2">
                <p>RA Al Barokah Mmpunyai banyak fasilitas (Deskripsi Anda di sini)</p>
            </div>
        </div>
        {{-- ... Item 3, 4, 5, 6 (Sama seperti sebelumnya) ... --}}
        <div class="ppdb-faq-item">
            <div class="ppdb-faq-header" data-target="#faq3">
                <div class="faq-title-content"><span class="faq-count">3</span><span>Guru Yang Berpengalaman</span></div>
                <span class="faq-toggle-icon">+</span>
            </div>
            <div class="ppdb-faq-body" id="faq3"><p>Guru di RA Al Barokah sangat ramah. (Deskripsi Anda di sini)</p></div>
        </div>
        <div class="ppdb-faq-item">
            <div class="ppdb-faq-header" data-target="#faq4">
                <div class="faq-title-content"><span class="faq-count">4</span><span>Di Naungi Kementrian Agama</span></div>
                <span class="faq-toggle-icon">+</span>
            </div>
            <div class="ppdb-faq-body" id="faq4"><p>RA Al Barokah di naungi kementrian agama (Deskripsi Anda di sini)</p></div>
        </div>
        <div class="ppdb-faq-item">
            <div class="ppdb-faq-header" data-target="#faq5">
                <div class="faq-title-content"><span class="faq-count">5</span><span>Alumni Berakhlaq Baik dan Mulia</span></div>
                <span class="faq-toggle-icon">+</span>
            </div>
            <div class="ppdb-faq-body" id="faq5"><p>Lulusan RA Al Barokah berakhlaq mulia (Deskripsi Anda di sini)</p></div>
        </div>
        <div class="ppdb-faq-item">
            <div class="ppdb-faq-header" data-target="#faq6">
                <div class="faq-title-content"><span class="faq-count">6</span><span>Memiliki Banyak Prestasi</span></div>
                <span class="faq-toggle-icon">+</span>
            </div>
            <div class="ppdb-faq-body" id="faq6"><p>RA Al Barokah memiliki banyak prestasi (Deskripsi Anda di sini)</p></div>
        </div>
    </div>
</section>

{{-- SCRIPT KHUSUS UNTUK FAQ (Accordion) --}}
<script>
    if (!window.faqScriptLoaded) {
        document.addEventListener('DOMContentLoaded', function() {
            const faqHeaders = document.querySelectorAll('.ppdb-faq-header');

            faqHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const targetBody = document.querySelector(this.getAttribute('data-target'));
                    const isActive = this.classList.contains('active');

                    faqHeaders.forEach(h => {
                        if (h !== this) {
                            h.classList.remove('active');
                            h.querySelector('.faq-toggle-icon').textContent = '+';
                            h.querySelector('.faq-toggle-icon').style.transform = 'rotate(0deg)';
                            const otherBody = document.querySelector(h.getAttribute('data-target'));
                            if (otherBody) otherBody.style.display = 'none';
                        }
                    });

                    if (!isActive) {
                        this.classList.add('active');
                        this.querySelector('.faq-toggle-icon').textContent = '−';
                        this.querySelector('.faq-toggle-icon').style.transform = 'rotate(45deg)';
                        if (targetBody) targetBody.style.display = 'block';
                    } else {
                        this.classList.remove('active');
                        this.querySelector('.faq-toggle-icon').textContent = '+';
                        this.querySelector('.faq-toggle-icon').style.transform = 'rotate(0deg)';
                        if (targetBody) targetBody.style.display = 'none';
                    }
                });
            });
            
            // Buka item pertama secara default
            const firstHeader = document.querySelector('.ppdb-faq-header');
            if (firstHeader) {
                firstHeader.click();
            }
        });
        window.faqScriptLoaded = true;
    }
</script>