<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' || SiPekan' : 'SiPekan' }}</title>

    {{-- font --}}
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    {{-- font --}}

    {{-- icon --}}
    <link rel="shortcut icon" href="{{ asset('dist/images/logo/logo-poliwangi.png') }}" type="image/x-icon">
    {{-- end icon --}}

    {{-- css --}}

    <link rel="stylesheet" href="{{ asset('dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/vendors/iconly/bold.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/vendors/perfect-scrollbar/perfect-scrollbar.css') }}" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/vendors/bootstrap-icons/bootstrap-icons.css') }}" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/vendors/simple-datatables/style.css') }}" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/vendors/sweetalert2/sweetalert2.min.css') }}" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/vendors/fontawesome/all.min.css') }}" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/vendors/choices.js/choices.min.css') }}" media="print"
        onload="this.media='all'" />
    <link rel="stylesheet" href="{{ asset('dist/css/index.css') }}" media="print" onload="this.media='all'">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


    @stack('styles')
    @stack('css')
    {{-- end css --}}
</head>

<body>
    <div id="app">
        {{-- sidebar --}}
        @section('sidebar_be')
            @include('components.sidebar_be')
        @show
        {{-- end sidebar --}}

        <div id="main">
            {{-- navbar --}}
            @section('navbar_be')
                @include('components.navbar_be')
            @show
            {{-- end navbar --}}

            <div class="page-heading mt-5">
                {{-- breadcrumb --}}
                @section('breadcrumb_be')
                    @include('components.breadcrumb_be')
                @show
                {{-- end breadcrumb --}}

                {{-- content main --}}
                @yield('backend_main')
                {{-- end content main --}}

                {{-- footer --}}
                @section('footer_be')
                    @include('components.footer_be')
                @show
                {{-- end footer --}}
            </div>
        </div>
    </div>

    {{-- javascript --}}
    <script defer src="{{ asset('dist/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script defer src="{{ asset('dist/js/bootstrap.bundle.min.js') }}"></script>
    <script defer src="{{ asset('dist/js/main.js') }}"></script>
    <script defer src="{{ asset('dist/vendors/choices.js/choices.min.js') }}"></script>
    <script defer src="{{ asset('dist/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script defer src="{{ asset('dist/vendors/fontawesome/all.min.js') }}"></script>
    @stack('scripts')
    @stack('script')
    {{-- end javascript --}}
</body>

</html>
