<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Rekomendasi Pengadaan Buku Pada Perpustakaan Politeknik Negeri Banyuwangi</title>

    {{-- font --}}
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    {{-- font --}}

    {{-- icon --}}
    <link rel="shortcut icon" href="{{ asset('dist/images/logo/logo-poliwangi.png') }}" type="image/x-icon">
    {{-- end icon --}}

    {{-- css --}}
    <link rel="stylesheet" href="{{ asset('dist/css/bootstrap.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/vendors/bootstrap-icons/bootstrap-icons.css') }}" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/vendors/simple-datatables/style.css') }}" media="print">
</head>

<body>
    <div class="d-flex align-items-center mt-4">
        <img src="{{ asset('dist/images/logo/logo-poliwangi.png') }}" class="me-2" alt="Logo"
            style="max-height: 75px; height: 75px;">
        <div class="ms-2">
            <div class="text-dark fw-bold">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET DAN TEKNOLOGI</div>
            <div class="text-dark fw-bold">POLITEKNIK NEGERI BANYUWANGI
            </div>
        </div>
    </div>

    <div class="mt-3">
        @yield('content_print')
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
    {{-- <script defer src="{{ asset('dist/vendors/simple-datatables/simple-datatables.js') }}"></script> --}}
    {{-- <script defer src="{{ asset('dist/js/data_tables.js') }}"></script> --}}
</body>

</html>
