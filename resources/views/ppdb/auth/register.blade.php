@extends('auth.layout-auth')

@section('title', 'Register')

@section('content')
<style>
    .auth-wrapper {
        position: relative;
        overflow: hidden;
        /* Gradasi hijau yang lebih lembut */
        background: linear-gradient(120deg, #e8f5e9 0%, #388E3C 100%);
        min-height: 100vh;
    }

    .floating-circle {
        position: absolute;
        font-size: 4rem;
        opacity: 0.4;
        z-index: 0;
        filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
    }

    /* Varian warna hijau untuk setiap lingkaran */
    .circle-1 { top: 5%; left: 5%; animation: float 4s ease-in-out infinite; color: #4CAF50; }
    .circle-2 { top: 15%; left: 35%; animation: float 3.5s ease-in-out infinite 0.2s; color: #81C784; }
    .circle-3 { top: 35%; left: 15%; animation: float 4.5s ease-in-out infinite 0.4s; color: #66BB6A; }
    .circle-4 { top: 55%; left: 35%; animation: float 3.8s ease-in-out infinite 0.6s; color: #A5D6A7; }
    .circle-5 { top: 75%; left: 5%; animation: float 4.2s ease-in-out infinite 0.8s; color: #2E7D32; }
    .circle-6 { top: 25%; left: 55%; animation: float 3.6s ease-in-out infinite 1s; color: #43A047; }
    .circle-7 { top: 45%; left: 65%; animation: float 4.1s ease-in-out infinite 1.2s; color: #388E3C; }
    .circle-8 { top: 65%; left: 55%; animation: float 3.9s ease-in-out infinite 1.4s; color: #1B5E20; }
    .circle-9 { top: 85%; left: 75%; animation: float 4.3s ease-in-out infinite 1.6s; color: #C8E6C9; }
    .circle-10 { top: 10%; left: 85%; animation: float 4s ease-in-out infinite 1.8s; color: #81C784; }
    .circle-11 { top: 30%; left: 80%; animation: float 3.7s ease-in-out infinite 2s; color: #A5D6A7; }
    .circle-12 { top: 50%; left: 90%; animation: float 4.4s ease-in-out infinite 2.2s; color: #66BB6A; }
    .circle-13 { top: 70%; left: 85%; animation: float 3.8s ease-in-out infinite 2.4s; color: #4CAF50; }
    .circle-14 { top: 90%; left: 90%; animation: float 4.2s ease-in-out infinite 2.6s; color: #2E7D32; }
    .circle-15 { top: 20%; left: 25%; animation: float 3.9s ease-in-out infinite 2.8s; color: #1B5E20; }

    @keyframes float {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
            opacity: 0.4;
        }
        50% {
            transform: translateY(-30px) rotate(10deg);
            opacity: 0.8;
        }
    }

    .auth-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: stretch;
        margin: 0px auto;
        max-width: 1100px;
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
        overflow: hidden;
        min-height: calc(100vh - 40px);
    }

    .login-section {
        flex: 1;
        min-width: 300px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        padding: 30px;
        width: 100%;
        max-width: 500px;
    }

    .image-section {
        flex: 1;
        min-width: 300px;
        background-color: #ffffff;
        background-image: url('/Assets/Frontend/img/cover_login.png');
        background-size: 90%;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 0 20px 20px 0;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    body {
        overflow-x: hidden;
    }

    .btn-primary {
        /* Warna tombol utama */
        background-color: #388E3C !important;
        border-color: #388E3C !important;
    }

    .btn-primary:hover {
        /* Warna hover tombol */
        background-color: #1B5E20 !important;
    }

    .text-primary {
        /* Warna teks utama */
        color: #388E3C !important;
    }

    a {
        /* Warna tautan */
        color: #388E3C;
    }

    a:hover {
        /* Warna hover tautan */
        color: #1B5E20;
    }

    .form-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-row .form-group {
        flex: 1;
        margin-bottom: 1rem;
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        
        .login-section {
            order: 1;
            min-height: auto;
        }
        
        .image-section {
            order: 2;
            min-height: 250px;
            border-radius: 0 0 20px 20px;
            background-size: 95%;
        }
        
        .login-card {
            padding: 20px;
        }

        .form-row {
            flex-direction: column;
            gap: 0;
        }
    }

    @media (max-width: 768px) {
        .auth-wrapper {
            min-height: 100vh;
        }
        
        .login-card {
            padding: 15px;
        }
        
        .floating-circle {
            font-size: 2rem;
        }
        
        .image-section {
            min-height: 200px;
            background-size: 100%;
        }

        .form-row {
            flex-direction: column;
            gap: 0;
        }
    }

    @media (max-width: 576px) {
        
        .login-card {
            border-radius: 10px;
            padding: 15px;
        }
        
        .image-section {
            border-radius: 0 0 10px 10px;
            min-height: 180px;
            background-size: 100%;
        }
        
        .floating-circle {
            font-size: 1.5rem;
        }
    }

    /* Ensure image is visible on large screens */
    @media (min-width: 992px) {
        
        .login-section {
            flex: 0 0 50%;
        }
        
        .image-section {
            flex: 0 0 50%;
            border-radius: 0 20px 20px 0;
            min-height: 500px;
            background-size: 85%;
        }
    }

    @media (min-width: 1200px) {
        .image-section {
            min-height: 600px;
            background-size: 80%;
        }
    }
</style>

<div class="auth-wrapper">
    <div class="floating-circle circle-1">⚪</div>
    <div class="floating-circle circle-2">⚪</div>
    <div class="floating-circle circle-3">⚪</div>
    <div class="floating-circle circle-4">⚪</div>
    <div class="floating-circle circle-5">⚪</div>
    <div class="floating-circle circle-6">⚪</div>
    <div class="floating-circle circle-7">⚪</div>
    <div class="floating-circle circle-8">⚪</div>
    <div class="floating-circle circle-9">⚪</div>
    <div class="floating-circle circle-10">⚪</div>
    <div class="floating-circle circle-11">⚪</div>
    <div class="floating-circle circle-12">⚪</div>
    <div class="floating-circle circle-13">⚪</div>
    <div class="floating-circle circle-14">⚪</div>
    <div class="floating-circle circle-15">⚪</div>
    
    <div class="auth-inner">
        <div class="login-section">
            <div class="login-card">
                <a class="brand-logo d-flex align-items-center mb-3" href="/">
                    <img src="{{ asset('Assets/Frontend/img/foto_logo.png') }}" alt="Logo" width="50" height="50">
                    <h2 class="brand-text text-primary ml-2 mb-0">RA Al Barokah</h2>      
                </a>
                
                @if($message = Session::get('error'))
                    <div class="alert alert-danger">
                        <div class="alert-body">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="close" data-dismiss="alert">×</button>
                        </div>
                    </div>
                @elseif($message = Session::get('success'))
                    <div class="alert alert-success">
                        <div class="alert-body">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="close" data-dismiss="alert">×</button>
                        </div>
                    </div>
                @endif

                <h2 class="card-title font-weight-bold mb-1">Welcome to RA Al Barokah! 👋</h2>
                <p class="card-text mb-3">Pendaftaran PPDB RA Al Barokah</p>

                <form class="auth-login-form" action="{{ route('register.store') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="register-name">Nama Lengkap</label>
                            <input type="text" id="register-name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Masukan Nama Lengkap" autofocus>
                            @error('name')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="register-email">Email</label>
                            <input type="email" id="register-email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Masukan Email">
                            @error('email')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Bagian form password --}}
<div class="form-row">
    <div class="form-group">
        <label for="register-whatsapp">No WhatsApp</label>
        <input type="text" id="register-whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="08123456789">
        @error('whatsapp')
            <span class="invalid-feedback d-block">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="register-password">Password Login</label>
        <div class="input-group input-group-merge form-password-toggle">
            <input type="password" id="register-password" class="form-control form-control-merge @error('password') is-invalid @enderror" name="password" placeholder="············">
            <div class="input-group-append"><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span></div>
        </div>
        <small class="form-text text-muted mt-1">
            <i class="fas fa-info-circle"></i> Min. 8 karakter, huruf besar/kecil,dan angka 
        </small>
        @error('password')
            <span class="invalid-feedback d-block">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="register-confirm-password">Konfirmasi Password</label>
    <div class="input-group input-group-merge form-password-toggle">
        <input type="password" id="register-confirm-password" class="form-control form-control-merge @error('confirm_password') is-invalid @enderror" name="confirm_password" placeholder="············">
        <div class="input-group-append"><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span></div>
    </div>
    @error('confirm_password')
        <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>

                    <div class="form-group form-check">
                        <input class="form-check-input" id="remember-me" type="checkbox">
                        <label class="form-check-label" for="remember-me">Remember Me</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">Daftar</button>
                </form>

                <p class="text-center">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk Sekarang</a>
                </p>
            </div>
        </div>
        <div class="image-section">
        </div>
        </div>
</div>
@endsection

<a href="https://wa.me/6281336332888" class="whatsapp-button" target="_blank">
    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
    Hubungi Kami
</a>

<style>
    .whatsapp-button {
        display: inline-flex;
        align-items: center;
        background-color: #25D366;
        color: white;
        padding: 10px 20px;
        border-radius: 30px;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }
    .whatsapp-button img {
        width: 24px;
        height: 24px;
        margin-right: 10px;
    }
</style>
