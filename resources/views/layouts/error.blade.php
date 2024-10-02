<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- title --}}
    <title>Sistem Usulan Buku Pustaka</title>
    {{-- end title --}}

    {{-- icon --}}
    <link rel="shortcut icon" href="{{ asset('dist/images/logo/logo-poliwangi.png') }}" type="image/x-icon">
    {{-- end icon --}}

    {{-- font --}}
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    {{-- end font --}}

    {{-- css --}}
    <link rel="stylesheet" href="{{ asset('dist/css/bootstrap.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/vendors/bootstrap-icons/bootstrap-icons.css') }}" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('dist/css/pages/error.css') }}" media="print" onload="this.media='all'">
    {{-- end css --}}
</head>

<body>
    <div id="error">
        <div class="error-page container">
            <div class="col-md-8 col-12 offset-md-2">
                @yield('error_main')
            </div>
        </div>
    </div>
</body>

</html>
