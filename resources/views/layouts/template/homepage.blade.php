{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'SITA-BI Homepage')</title>

    <!-- Favicons -->
    <link href="{{ asset('assets-homepage/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets-homepage/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&family=Poppins:wght@300;400;700&family=Nunito:wght@300;400;700&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets-homepage/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets-homepage/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets-homepage/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets-homepage/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets-homepage/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    @stack('styles')

    <!-- Main CSS File -->
    <link href="{{ asset('assets-homepage/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">
    {{-- Header --}}
    @include('layouts.components.border-homepage.header')

    {{-- Main Content --}}
    <main class="main">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.components.border-homepage.footer')

    {{-- Scroll Top --}}
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

</body>

{{-- Vendor JS Files --}}
<script src="{{ asset('assets-homepage/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets-homepage/vendor/php-email-form/validate.js') }}"></script>
<script src="{{ asset('assets-homepage/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('assets-homepage/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('assets-homepage/vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('assets-homepage/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('assets-homepage/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('assets-homepage/vendor/swiper/swiper-bundle.min.js') }}"></script>

@stack('scripts')

{{-- Main JS File --}}
<script src="{{ asset('assets-homepage/js/main.js') }}"></script>

</html>
