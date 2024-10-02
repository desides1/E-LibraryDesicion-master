<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $title }} || Sistem Rekomendasi Pengadaan Buku Perpustakaan</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- icon --}}
    <link href="{{ asset('dist/images/logo/logo-poliwangi.png') }}" rel="icon">
    <link rel="shortcut icon" href="{{ asset('dist/images/logo/logo-poliwangi.png') }}" type="image/x-icon">
    {{-- end icon --}}

    {{-- fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    {{-- end fonts --}}

    {{-- css --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet"
        media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/vendors/bootstrap-icons/bootstrap-icons.css') }}" media="print"
        onload="this.media='all'">
    <link href="{{ asset('components/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet" media="print"
        onload="this.media='all'">
    <link href="{{ asset('components/lib/animate/animate.min.css') }}" rel="stylesheet" media="print"
        onload="this.media='all'">
    <link href="{{ asset('components/css/bootstrap.min.css') }}" rel="stylesheet" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/vendors/sweetalert2/sweetalert2.min.css') }}" media="print"
        onload="this.media='all'">
    <link href="{{ asset('components/css/style.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('styles')
    {{-- end css --}}
</head>

<body>
    {{-- loading --}}
    @section('loading_fe')
        @include('components.loading')
    @show
    {{-- end loading --}}

    {{-- navigation bar --}}
    @section('navbar_fe')
        @include('components.navigation_fe')
    @show
    {{-- end navigation bar --}}

    @yield('content_fe')

    {{-- footer --}}
    @section('footer_fe')
        @include('components.footer_fe')
    @show
    {{-- end footer --}}

    {{-- top navigation --}}
    @section('top_navigation_fe')
        @include('components.top_navigation')
    @show
    {{-- end top navigation --}}


    {{-- javascript --}}
    <script defer src="{{ asset('components/lib/wow/wow.min.js') }}"></script>
    <script defer src="{{ asset('dist/js/bootstrap.bundle.min.js') }}"></script>
    <script defer src="{{ asset('components/lib/easing/easing.min.js') }}"></script>
    <script defer src="{{ asset('components/lib/waypoints/waypoints.min.js') }}"></script>
    <script defer src="{{ asset('components/lib/counterup/counterup.min.js') }}"></script>
    <script defer src="{{ asset('components/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script defer src="{{ asset('dist/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script defer src="{{ asset('components/js/main.js') }}"></script>
    @stack('scripts')
    {{-- end javascript --}}
</body>

</html>
