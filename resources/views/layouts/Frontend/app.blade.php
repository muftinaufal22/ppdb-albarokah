<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
    @include('layouts.Frontend.style')
    {{-- Ini untuk memuat CSS khusus dari halaman spesifik seperti halaman prestasi --}}
    @yield('head')
</head>

<body>
    <div id="preloader"></div>
    <div id="wrapper">
        <header>
            @include('frontend.content.header')
        </header>
        {{-- Konten utama dari halaman spesifik akan dimuat di sini --}}
        @yield('content')
        
        <div class="slider1-area overlay-default">
            @yield('slider')
        </div>
        @yield('about')
        @yield('video')
        @yield('guru')
        @yield('beritaEvent')
        @yield('prestasi')
        @yield('prestasi-detail')
        @yield('struktur')
        <footer>
            @include('frontend.content.footer')
        </footer>
        </div>
    @include('layouts.Frontend.scripts')
</body>

</html>