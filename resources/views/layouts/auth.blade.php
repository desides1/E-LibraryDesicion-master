<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>{{ isset($title) ? $title . ' || Sistem Usulan Buku Pustaka' : 'Sistem Usulan Buku Pustaka' }}</title>
    <meta name="description" content="Sistem Rekomendasi Pengadaan Buku Pada Perpustakaan Politeknik Negeri Banyuwangi" />

    {{-- icon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('dist/images/logo/logo-poliwangi.png') }}" />
    {{-- end icon --}}

    {{-- font --}}
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    {{-- end font --}}

    {{-- css --}}
    <link rel="stylesheet" href="{{ asset('auth/vendor/fonts/boxicons.css') }}" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('auth/vendor/css/core.css') }}" class="template-customizer-core-css"
        media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="{{ asset('auth/vendor/css/theme-default.css') }}" class="template-customizer-theme-css"
        media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="{{ asset('auth/css/demo.css') }}" media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="{{ asset('auth/vendor/css/pages/page-auth.css') }}" media="print"
        onload="this.media='all'" />
    @stack('styles')
    {{-- end css --}}

    {{-- helpers js --}}
    <script defer src="{{ asset('auth/vendor/js/helpers.js') }}"></script>
    <script defer src="{{ asset('auth/js/config.js') }}"></script>
    {{-- end helpers js --}}
</head>

<body>
    {{-- auth content --}}
    @yield('auth_content')
    {{-- end auth content --}}

    {{-- javascript --}}
    <script defer src="{{ asset('auth/vendor/libs/jquery/jquery.js') }}"></script>
    <script defer src="{{ asset('auth/vendor/libs/popper/popper.js') }}"></script>
    <script defer src="{{ asset('auth/vendor/js/bootstrap.js') }}"></script>
    <script defer src="{{ asset('auth/js/main.js') }}"></script>
    <script defer src="https://buttons.github.io/buttons.js"></script>
    @stack('scripts')
    {{-- end javascript --}}
</body>

</html>
