@extends('ppdb::layouts.master')

@section('title')
    Penerimaan Siswa Didik Baru
@endsection

@section('content')
    
    {{-- Bagian 1: Hero (Latar Belakang Gambar) --}}
    @include('ppdb::frontend.content.slider')

    {{-- Bagian 2: Persyaratan (Latar Belakang Gradien + Blob) --}}
    @include('ppdb::frontend.content.count')

    {{-- Bagian 3: Alur (Latar Belakang Putih Polos) --}}
    @include('ppdb::frontend.content.studi')

    {{-- Bagian 4: FAQ (Latar Belakang Parallax) --}}
    @include('ppdb::frontend.content.why')

@endsection

@push('scripts')
{{-- SCRIPT ANIMASI (Ambil dari beranda Anda) --}}
{{-- Anda bisa letakkan ini di file JS utama Anda jika mau --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Logika untuk 'animate-on-scroll'
        const targets = document.querySelectorAll('.animate-on-scroll');
        
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    // Anda bisa hapus 'transform' jika sudah di-handle CSS
                    entry.target.style.transform = 'translateY(0)'; 
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 }); // Muncul saat 10% terlihat
        
        targets.forEach(target => {
            // Sembunyikan elemen & siapkan untuk animasi
            target.style.opacity = '0';
            target.style.transform = 'translateY(50px)';
            target.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
            observer.observe(target);
        });

        // Logika untuk 'animate-on-scroll' (jika sudah 'is-visible')
        // Ini untuk memastikan elemen yang sudah di-load tidak 'blank'
        document.querySelectorAll('.animate-on-scroll.is-visible').forEach(target => {
            target.style.opacity = '1';
            target.style.transform = 'translateY(0)';
        });
    });
</script>
@endpush