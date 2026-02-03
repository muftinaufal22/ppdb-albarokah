{{-- resources/views/auth/reset-password.blade.php --}}
@extends('auth.layout-auth')

@section('title', 'Reset Password')

@section('content')
<style>
    .auth-wrapper {
        position: relative;
        overflow: hidden;
        background: linear-gradient(120deg, #e8f5e9 0%, #615AC8 100%);
        min-height: 100vh;
    }

    .floating-circle {
        position: absolute;
        font-size: 4rem;
        opacity: 0.4;
        z-index: 0;
        filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
    }

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
        max-width: 400px;
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
        background-color: #615AC8 !important;
        border-color: #615AC8 !important;
    }

    .btn-primary:hover {
        background-color: #3d4b70 !important;
    }

    .text-primary {
        color: #615AC8 !important;
    }

    a {
        color: #615AC8;
    }

    a:hover {
        color: #3d4b70;
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
    }

    @media (max-width: 576px) {
        .auth-inner {
            border-radius: 10px;
        }
        
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
        .auth-inner {
            flex-direction: row;
        }
        
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
        <!-- Reset Password Section -->
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

                <h2 class="card-title font-weight-bold mb-1">Reset Password 🔑</h2>
                <p class="card-text mb-3">Masukkan email dan password baru Anda</p>

                <form class="auth-reset-form" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" placeholder="Masukan Email" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <div class="input-group input-group-merge form-password-toggle">
                            <input id="password" type="password" class="form-control form-control-merge @error('password') is-invalid @enderror" name="password" placeholder="············" required autocomplete="new-password">
                            <div class="input-group-append"><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span></div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password-confirm">Konfirmasi Password Baru</label>
                        <div class="input-group input-group-merge form-password-toggle">
                            <input id="password-confirm" type="password" class="form-control form-control-merge" name="password_confirmation" placeholder="············" required autocomplete="new-password">
                            <div class="input-group-append"><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span></div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">Reset Password</button>
                </form>

                <p class="text-center">
                    Ingat password? <a href="{{ route('login') }}">Masuk Sekarang</a>
                </p>
            </div>
        </div>
        <!-- /Reset Password Section -->

        <!-- Image Section -->
        <div class="image-section">
        </div>
        <!-- /Image Section -->
    </div>
</div>
@endsection