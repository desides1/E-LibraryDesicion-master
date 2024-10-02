@extends('layouts.error')

@section('error_main')
    <img class="img-error" src="{{ asset('dist/images/error/error-500.png') }}" alt="Not Found">
    <div class="text-center">
        <h1 class="error-title">500 Internal Server Error</h1>
        <p class="fs-5 text-gray-600">Terjadi kesalahan pada server. Mohon coba lagi nanti.</p>
        <a href="{{ Auth::check() ? url('/dashboard') : url('/') }}" class="btn btn-lg btn-outline-primary mt-3">
            {{ Auth::check() ? 'Kembali ke Dashboard' : 'Kembali ke Beranda' }}
        </a>
    </div>
@endsection
