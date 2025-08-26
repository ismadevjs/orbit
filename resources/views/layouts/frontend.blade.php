<!DOCTYPE html>
<html lang="en" @yield('html_attribute')>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? '' }}</title>

    {{-- ===== FAB ICON ===== --}}
    @isset($logo5)
        <link rel="shortcut icon" href="{{ asset('esoft/img/logo/title5.png') }}" type="image/x-icon">
    @elseif(isset($logo3))
        <link rel="shortcut icon" href="{{ asset('esoft/img/logo/titile3.png') }}" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="{{ asset('esoft/img/logo/titel1.png') }}" type="image/x-icon">
    @endisset

    <!--=====CSS=======-->
    <link rel="stylesheet" href="{{ asset('esoft/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('esoft/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('esoft/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('esoft/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('esoft/css/slick-slider.css') }}">
    <link rel="stylesheet" href="{{ asset('esoft/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('esoft/css/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('esoft/css/mobile-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('esoft/css/main.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!--=====JQUERY=======-->
    <script src="{{ asset('esoft/js/jquery-3-7-1.min.js') }}"></script>
</head>

<body class="body2 body @yield('body_attribute')">

    @include('frontend.components.loader')

    @include('frontend.components.header')

    @yield('content')

    @include('frontend.components.cta')

    @include('frontend.components.footer')

    <!--=== js === -->
    <script src="{{ asset('esoft/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('esoft/js/aos.js') }}"></script>
    <script src="{{ asset('esoft/js/fontawesome.js') }}"></script>
    <script src="{{ asset('esoft/js/jquery.countup.js') }}"></script>
    <script src="{{ asset('esoft/js/mobile-menu.js') }}"></script>
    <script src="{{ asset('esoft/js/jquery.magnific-popup.js') }}"></script>
    <script src="{{ asset('esoft/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('esoft/js/slick-slider.js') }}"></script>
    <script src="{{ asset('esoft/js/gsap.min.js') }}"></script>
    <script src="{{ asset('esoft/js/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('esoft/js/Splitetext.js') }}"></script>
    <script src="{{ asset('esoft/js/text-animation.js') }}"></script>
    <script src="{{ asset('esoft/js/SmoothScroll.js') }}"></script>
    <script src="{{ asset('esoft/js/tilt.jquery.js') }}"></script>
    <script src="{{ asset('esoft/js/main.js') }}"></script>

</body>
</html>
