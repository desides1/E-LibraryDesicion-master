<div class="container-fluid position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-dark px-5 text-dark py-3 py-lg-0" style="background: white">
        <a href="{{ url('/') }}" class="navbar-brand p-0">
            <img src="{{ asset('dist/images/logo/logo-poliwangi.png') }}" class="my-2 logo-2" alt="" width="75">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="{{ url('/') }}"
                    class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">Beranda</a>
                <a href="{{ url('/front-book') }}"
                    class="nav-item nav-link {{ Request::is('front-book*') ? 'active' : '' }}">Koleksi Perpus</a>
                <a href="{{ url('/front-request') }}"
                    class="nav-item nav-link {{ Request::is('front-request*') ? 'active' : '' }}"><i
                        class="fa fa-book-reader me-2"></i>Usulan Buku</a>
                <a href="{{ url('/front-information') }}"
                    class="nav-item nav-link {{ Request::is('front-information*') ? 'active' : '' }}">Informasi</a>
            </div>
            <a href="{{ Auth::check() ? url('/dashboard') : url('/login') }}"
                class="btn {{ Auth::check() ? 'text-primary fw-bold' : 'text-dark' }} ms-3">
                <i class="fa fa-{{ Auth::check() ? 'user me-1' : 'sign-in-alt' }}"></i>
                {{ Auth::check() ? Auth::user()->name : '' }}
            </a>
        </div>
    </nav>

    {{-- carousel --}}
    @if (Request::is('/'))
        @section('carousel_fe')
            @include('components.carousel')
        @show
    @else
        <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
            <div class="row py-5">
                <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                    <h1 class="display-4 text-white animated zoomIn">{{ $title_head }}</h1>
                    <a href="{{ url('/') }}" class="h5 text-white">Beranda</a>
                    <i class="far fa-circle text-white px-2"></i>
                    <a href="" class="h5 text-white">{{ $title }}</a>
                </div>
            </div>
        </div>
    @endif  
    {{-- end carousel --}}
</div>
