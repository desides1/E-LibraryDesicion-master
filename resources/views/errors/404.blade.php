@extends('layouts.error')

@section('error_main')
    <img class="img-error" src="{{ asset('dist/images/error/error-404.png') }}" alt="Not Found">
    <div class="text-center">
        <h1 class="error-title">404 Not Found</h1>
        <p class="fs-5 text-gray-600">Halaman yang Anda cari tidak dapat ditemukan.</p>
        <a href="{{ Auth::check() ? url('/dashboard') : url('/') }}" class="btn btn-lg btn-outline-primary mt-3">
            {{ Auth::check() ? 'Kembali ke Dashboard' : 'Kembali ke Beranda' }}
        </a>
    </div>
@endsection
