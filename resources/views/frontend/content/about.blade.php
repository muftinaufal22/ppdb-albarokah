<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RA AL BAROKAH - Animasi</title>
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
        /* General Styling */
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            color: #333;
            background-color: #ffffff;
            overflow-x: hidden;
        }

        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }

        /* === HEADER IMAGE === */
        .top-image-header {
            position: relative;
            width: 100%;
            height: 700px;
            overflow: hidden;
            background-image: url('{{ asset('storage/images/slider/bacground.png') }}');
            background-size: cover;
            background-position: center bottom;
            z-index: 10;
            /* Efek bayangan di bawah gambar agar batas terlihat */
            filter: drop-shadow(0px 10px 15px rgba(0,0,0,0.15)); 
        }
        
        /* Video Background Fixed */
        .video-background {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
        }
        .video-background video {
            position: absolute;
            top: 50%; left: 50%;
            min-width: 100%; min-height: 100%;
            width: auto; height: auto;
            transform: translate(-50%, -50%);
            object-fit: cover;
        }

        section {
            padding: 80px 10%;
            position: relative;
        }

        /* === WRAPPER KONTEN (WARNA ABU MUDA AGAR KONTRAS) === */
        .unified-background-wrapper {
            position: relative;
            background-color: #eff5f3; /* Abu-hijau muda */
            padding-top: 80px;
            padding-bottom: 80px;
            overflow: hidden;
            box-shadow: inset 0 20px 30px -10px rgba(0,0,0,0.05);
        }

        .background-blob {
            position: absolute;
            top: -10%; left: -20%; width: 140%; height: 120%;
            z-index: 0;
            opacity: 0.5;
        }
        
        .welcome-section, .vision-section {
            position: relative;
            z-index: 1;
            padding-top: 40px;
            padding-bottom: 40px;
            background: none !important;
        }

        /* Kartu Welcome */
        .welcome-content {
            display: flex; gap: 2.5rem; align-items: center;
        }
        .welcome-text {
            flex: 1.2;
            background-color: #ffffff;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(1, 74, 54, 0.1);
            line-height: 1.8;
            border: 1px solid rgba(255,255,255,0.6);
        }
        .welcome-text h2 {
            font-size: 2.5rem; color: #014A36; margin-top: 0; margin-bottom: 1.5rem;
        }
        .signature {
            margin-top: 2rem; font-style: italic; color: #555; border-top: 1px solid #eee; padding-top: 1rem;
        }
        .signature p { margin: 0.2rem 0; }
        
        .welcome-image-placeholder {
            background-image: url('{{ asset('Assets/Frontend/img/kepsek.png') }}');
            flex: 0 0 35%; height: 550px;
            border-radius: 15px;
            background-size: cover; background-position: center;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            border: 5px solid #ffffff;
        }

        /* === BAGIAN VISI MISI === */
        .vision-content {
            display: flex; gap: 3rem; align-items: center;
        }
        .vision-images {
            flex: 0 0 40%; position: relative; height: 400px;
        }
        .vision-images .image-small-1 {
            position: absolute; width: 65%; height: 280px; 
            top: 0; left: 0;
            background-image: url('{{ asset('Assets/Frontend/img/anak.jpg') }}');
            background-size: cover; background-position: center;
            border-radius: 15px; z-index: 2;
            border: 6px solid #ffffff; /* Bingkai Putih */
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        .vision-images .image-small-2 {
            position: absolute; width: 75%; height: 300px; 
            bottom: 0; right: 0;
            background-image: url('{{ asset('Assets/Frontend/img/pentas.jpg') }}'); 
            background-size: cover; background-position: center;
            border-radius: 15px; z-index: 1;
            border: 6px solid #ffffff; /* Bingkai Putih */
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            background-color: #014A36;
        }

        .vision-text {
            flex: 1;
            background-color: #ffffff;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(1, 74, 54, 0.1);
            border: 1px solid rgba(255,255,255,0.6);
        }
        .vision-text h3 {
            font-family: 'Montserrat', sans-serif; font-weight: bold; color: #aaa;
            font-size: 0.9rem; letter-spacing: 2px; text-transform: uppercase;
        }
        .vision-text h2 {
            font-size: 2.2rem; color: #014A36; margin-top: 0.5rem; line-height: 1.3;
        }
        .vision-details {
            display: flex; margin-top: 2rem; gap: 2rem;
        }
        .vision-highlight {
            background: linear-gradient(135deg, #014A36 0%, #026e50 100%);
            color: white; padding: 1.5rem; text-align: center;
            border-radius: 12px; align-self: flex-start; min-width: 100px;
            box-shadow: 0 10px 20px rgba(1, 74, 54, 0.2);
        }
        .vision-highlight span { font-size: 3.5rem; font-weight: 700; display: block; line-height: 1; }
        .vision-highlight p { margin: 0; font-size: 0.9rem; }
        
        .vision-points ul { list-style: none; padding: 0; margin: 0; }
        .vision-points li {
            padding-left: 2rem; position: relative; margin-bottom: 0.8rem;
            line-height: 1.6; color: #555;
        }
        .vision-points li::before {
            content: '✓'; color: #fff; background-color: #014A36;
            width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;
            border-radius: 50%; font-size: 12px; position: absolute; left: 0; top: 3px;
        }

        /* === CSS FASILITAS (DIKEMBALIKAN KE STYLE AWAL/PUTIH) === */
        .achievements-section {
            background-image: url('{{ asset('storage/images/slider/testimoni.png') }}');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            color: white; 
            text-align: center; 
            padding-top: 60px; 
            padding-bottom: 60px;
            overflow: hidden;
            position: relative;
            /* Overlay hijau DIHAPUS agar kembali terang seperti awal */
        }
        
        .achievements-section .title-box { 
            display: inline-block; 
            background-color: rgba(255, 255, 255, 0.9); /* Putih Transparan (Awal) */
            padding: 15px 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); 
            margin-bottom: 3rem; 
            border: 3px solid #014A36; /* Border Hijau (Awal) */
        }
        .achievements-section h2 { 
            font-size: 2.5rem; 
            margin: 0; 
            line-height: 1.3; 
            color: #014A36; /* Teks Hijau (Awal) */
        }
        
        .achievements-container { 
            position: relative; width: 100%; overflow: hidden; z-index: 1; padding-bottom: 20px;
        }
        .achievements-wrapper { 
            display: flex; transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
            gap: 1.5rem; width: max-content; padding: 0 20px;
        }
        
        .achievement-placeholder { 
            flex: 0 0 280px; height: 250px; 
            background-color: rgba(255, 255, 255, 0.8); /* Background Putih Transparan (Awal) */
            border-radius: 8px; 
            transition: transform 0.3s ease, box-shadow 0.3s ease; 
            cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; 
            padding-bottom: 1.5rem; 
            font-size: 1.2rem; color: #fff; font-weight: bold; 
            text-shadow: 1px 1px 3px rgba(0,0,0,0.8); 
            background-size: cover; background-position: center; 
            position: relative; overflow: hidden;
        }
        .achievement-placeholder::after { 
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(0, 0, 0, 0.2); /* Overlay tipis original */
            border-radius: 8px; 
        }
        .achievement-placeholder span { position: relative; z-index: 1; }
        .achievement-placeholder:hover { 
            transform: scale(1.05); box-shadow: 0 10px 20px rgba(0,0,0,0.2); 
        }

        /* Gambar Fasilitas */
        .achievement-placeholder:nth-child(1) { background-image: url('{{ asset('Assets/Frontend/img/pentas.jpg') }}'); }
        .achievement-placeholder:nth-child(2) { background-image: url('{{ asset('Assets/Frontend/img/anak.jpg') }}'); }
        .achievement-placeholder:nth-child(3) { background-image: url('{{ asset('Assets/Frontend/img/anak.jpg') }}'); }
        .achievement-placeholder:nth-child(4) { background-image: url('{{ asset('Assets/Frontend/img/anak.jpg') }}'); }
        .achievement-placeholder:nth-child(5) { background-image: url('{{ asset('Assets/Frontend/img/kelas_A.png') }}'); }
        .achievement-placeholder:nth-child(6) { background-image: url('{{ asset('Assets/Frontend/img/kelas_B.png') }}'); }
        .achievement-placeholder:nth-child(7) { background-image: url('{{ asset('Assets/Frontend/img/anak.jpg') }}'); }
        .achievement-placeholder:nth-child(8) { background-image: url('{{ asset('Assets/Frontend/img/tempat_bermain.png') }}'); }
        .achievement-placeholder:nth-child(9) { background-image: url('{{ asset('Assets/Frontend/img/anak.jpg') }}'); }
        .achievement-placeholder:nth-child(10) { background-image: url('{{ asset('Assets/Frontend/img/kantin.png') }}'); }
        
        .scroll-nav { 
            display: flex; justify-content: center; align-items: center; 
            gap: 1rem; margin-top: 2rem; position: relative; z-index: 1;
        }
        .scroll-btn { 
            background-color: #014A36; /* Tombol Hijau (Awal) */
            color: white; width: 50px; height: 50px; border-radius: 50%; 
            cursor: pointer; display: flex; align-items: center; justify-content: center; 
            font-size: 1.5rem; transition: all 0.3s ease; border: none; 
        }
        .scroll-btn:hover { background-color: #014A36; transform: scale(1.1); }
        .scroll-btn:disabled { opacity: 0.3; cursor: not-allowed; }
        
        .scroll-indicators { display: flex; justify-content: center; gap: 0.5rem; }
        .indicator { 
            width: 12px; height: 12px; border-radius: 50%; 
            background-color: #2c8602ff; cursor: pointer; transition: background-color 0.3s ease; 
        }
        .indicator.active { background-color: #014A36; }

        /* Responsive */
        @media (max-width: 992px) {
            .welcome-content, .vision-content { flex-direction: column; }
            .vision-images { width: 100%; margin-bottom: 2rem; height: 350px; }
            .vision-images .image-small-1 { width: 60%; left: 10%; }
            .vision-images .image-small-2 { width: 60%; right: 10%; }
            .achievement-placeholder { flex: 0 0 250px; }
            .background-blob { display: none; }
            .top-image-header { height: 400px; }
        }
        @media (max-width: 768px) {
            .achievement-placeholder { flex: 0 0 220px; }
            .scroll-btn { width: 40px; height: 40px; font-size: 1.5rem; }
            section { padding: 40px 5%; }
            .welcome-text, .vision-text { padding: 2rem; }
        }
        @media (max-width: 480px) {
            .achievement-placeholder { flex: 0 0 200px; }
            .achievements-section .title-box { padding: 10px 20px; border: 2px solid #014A36; }
            .top-image-header { height: 250px; }
            .welcome-text h2, .vision-text h2 { font-size: 1.8rem; }
        }
        .achievements-wrapper::-webkit-scrollbar { display: none; }
    </style>
</head>
<body>

    <div class="video-background">
        <video autoplay loop muted playsinline>
            <source src="{{ asset('storage/images/slider/vidio_anak.mp4') }}" type="video/mp4">
            Browser Anda tidak mendukung tag video.
        </video>
    </div>

    <div class="top-image-header"></div>
    
    <main>
        <div class="unified-background-wrapper">
            
            <div class="background-blob">
                <svg viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg" width="100%">
                    <path fill="#dcece6">
                        <animate attributeName="d" dur="15s" repeatCount="indefinite" values="M440.5,320.5Q423,401,336.5,431Q250,461,168.5,433.5Q87,406,62,328Q37,250,65,173.5Q93,97,171.5,62Q250,27,330.5,63Q411,99,444.5,174.5Q478,250,440.5,320.5Z;
                        M451,323Q436,406,343,431Q250,456,174,432.5Q98,409,55,329.5Q12,250,55,170.5Q98,91,174,67Q250,43,338,68Q426,93,451,171.5Q476,250,451,323Z;
                        M426,316.5Q398,393,324,411Q250,429,174,420Q98,411,59.5,330.5Q21,250,58,172Q95,94,172.5,60Q250,26,327,51.5Q404,77,426,163.5Q448,250,426,316.5Z;
                        M440.5,320.5Q423,401,336.5,431Q250,461,168.5,433.5Q87,406,62,328Q37,250,65,173.5Q93,97,171.5,62Q250,27,330.5,63Q411,99,444.5,174.5Q478,250,440.5,320.5Z;"></animate>
                    </path>
                </svg>
            </div>

            <section class="welcome-section">
                <div class="welcome-content">
                    <div class="welcome-text animate-on-scroll">
                        <h2>Selamat datang di RA AL BAROKAH</h2>
                        <p>Puji syukur marilah kita panjatkan kehadirat Allah SWT atas berkat, rahmat dan hidayah-Nya sehingga salah satu program penting dalam proses perkembangan RA AL BAROKAH untuk memasuki era dunia maya dapat terwujud. Tujuan penting hadirnya RA AL BAROKAH di dunia maya yaitu dapat memberikan berbagai informasi kepada siswa, orang tua siswa dan masyarakat tentang kinerja sekolah serta memberikan masukan kritik dan saran yang membangun bagi kemajuan sekolah melalui peran serta masyarakat.</p>
                        <p>Di era digital ini, kami menyadari pentingnya komunikasi yang transparan dan akses informasi yang mudah. Website ini kami dedikasikan sebagai jendela utama untuk melihat segala aktivitas, pencapaian, serta program-program unggulan yang kami tawarkan.</p>
                        <p>RA AL BAROKAH merupakan lembaga pendidikan yang berusaha memberikan pelayanan pendidikan terbaik bagi anak-anak usia dini.</p>
                        <div class="signature">
                            <p>Hormat, Menyayangi,</p>
                            <p><strong>Kepala RA AL BAROKAH</strong></p>
                            <br>
                            <p><strong>Siti Fatimah, S.Pd</strong></p>
                        </div>
                    </div>
                    <div class="welcome-image-placeholder animate-on-scroll"></div>
                </div>
            </section>
    
            <section class="vision-section">
                <div class="vision-content">
                    <div class="vision-images animate-on-scroll">
                        <div class="image-small-1"></div>
                        <div class="image-small-2"></div>
                    </div>
                    <div class="vision-text animate-on-scroll">
                        <h3>VISI DAN MISI:</h3>
                        <h2>Terwujudnya Generasi Islam yang Berakhlak Mulia, Cerdas, Sehat, Mandiri, dan Berbudaya</h2>
                        <div class="vision-details">
                            <div class="vision-highlight">
                                <span>14</span>
                                <p>tahun<br>mengabdi</p>
                            </div>
                            <div class="vision-points">
                                <ul>
                                    <li>Melaksanakan pembelajaran aktif, kreatif, inovatif, efektif, dan menyenangkan</li>
                                    <li>Menjadi sekolah islami yang baik, berlandaskan aswaja</li>
                                    <li>Mengajarkan anak memahami tata cara/Adab dalam segala hal</li>
                                    <li>Mengembangkan dakwah melalui pendidikan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <section class="achievements-section">
            <div class="title-box animate-on-scroll">
                <h2>FASILITAS SEKOLAH</h2>
            </div>
            
            <div class="achievements-container animate-on-scroll">
                <div class="achievements-wrapper" id="facilitiesWrapper">
                    <div class="achievement-placeholder"><span>Pentas</span></div>
                    <div class="achievement-placeholder"><span>Tempat Parkir</span></div>
                    <div class="achievement-placeholder"><span>Aula</span></div>
                    <div class="achievement-placeholder"><span>Ruang UKS</span></div>
                    <div class="achievement-placeholder"><span>Ruang Kelas A</span></div>
                    <div class="achievement-placeholder"><span>Ruang Kelas B</span></div>
                    <div class="achievement-placeholder"><span>Lapangan</span></div>
                    <div class="achievement-placeholder"><span>Tempat Bermain</span></div>
                    <div class="achievement-placeholder"><span>Kamar Mandi</span></div>
                    <div class="achievement-placeholder"><span>Kantin</span></div>
                </div>
            </div>

            <div class="scroll-nav">
                <button class="scroll-btn" id="prevBtn">‹</button>
                <div class="scroll-indicators" id="indicators"></div>
                <button class="scroll-btn" id="nextBtn">›</button>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const targets = document.querySelectorAll('.animate-on-scroll');
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        entry.target.style.transform = 'translateY(0)';
                        entry.target.style.opacity = '1';
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            
            targets.forEach(target => {
                target.style.transform = 'translateY(50px)';
                target.style.opacity = '0';
                target.style.transition = 'all 0.8s ease-out';
                observer.observe(target);
            });

            const wrapper = document.getElementById('facilitiesWrapper');
            if (wrapper) { 
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                const indicatorsContainer = document.getElementById('indicators');
                
                const facilityItems = Array.from(wrapper.children);
                const totalItems = facilityItems.length;
                
                let currentIndex = 0;
                let itemsPerView = 3; 
                let maxIndex = Math.max(0, totalItems - itemsPerView);
                
                function updateResponsiveVariables() {
                    const screenWidth = window.innerWidth;
                    if (screenWidth < 480) { itemsPerView = 1; }
                    else if (screenWidth < 768) { itemsPerView = 2; }
                    else { itemsPerView = 3; }
                    maxIndex = Math.max(0, totalItems - itemsPerView);
                    if (currentIndex > maxIndex) { currentIndex = maxIndex; }
                }

                function createIndicators() {
                    indicatorsContainer.innerHTML = '';
                    const dotCount = maxIndex + 1;
                    for (let i = 0; i < dotCount; i++) {
                        const indicator = document.createElement('div');
                        indicator.classList.add('indicator');
                        if (i === currentIndex) { indicator.classList.add('active'); }
                        indicator.addEventListener('click', () => goToSlide(i));
                        indicatorsContainer.appendChild(indicator);
                    }
                }
                
                function updateSlider() {
                    if (facilityItems.length === 0) return;
                    const itemWidth = facilityItems[0].offsetWidth + parseFloat(window.getComputedStyle(wrapper).gap);
                    const translateX = currentIndex * itemWidth;
                    wrapper.style.transform = `translateX(-${translateX}px)`;
                    prevBtn.disabled = currentIndex === 0;
                    nextBtn.disabled = currentIndex >= maxIndex;
                    document.querySelectorAll('.indicator').forEach((indicator, index) => {
                        indicator.classList.toggle('active', index === currentIndex);
                    });
                }
                
                function goToSlide(index) {
                    currentIndex = Math.max(0, Math.min(index, maxIndex));
                    updateSlider();
                }
                
                prevBtn.addEventListener('click', () => { if (currentIndex > 0) { currentIndex--; updateSlider(); } });
                nextBtn.addEventListener('click', () => { if (currentIndex < maxIndex) { currentIndex++; updateSlider(); } });
                
                let startX = 0; let isDragging = false;
                wrapper.addEventListener('touchstart', (e) => { startX = e.touches[0].clientX; isDragging = true; }, { passive: true });
                wrapper.addEventListener('touchmove', (e) => { if (!isDragging) return; e.preventDefault(); }, { passive: false });
                wrapper.addEventListener('touchend', (e) => {
                    if (!isDragging) return;
                    isDragging = false;
                    const endX = e.changedTouches[0].clientX;
                    const diff = startX - endX;
                    if (Math.abs(diff) > 50) { 
                        if (diff > 0 && currentIndex < maxIndex) { currentIndex++; }
                        else if (diff < 0 && currentIndex > 0) { currentIndex--; }
                        updateSlider();
                    }
                }, { passive: true });
                
                updateResponsiveVariables();
                createIndicators();
                updateSlider();
                
                window.addEventListener('resize', () => {
                    updateResponsiveVariables();
                    createIndicators();
                    goToSlide(0);
                });
            }
        });
    </script>
</body>
</html>