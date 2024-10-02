@extends('layouts.error')

@section('error_main')
    <img class="img-error" src="{{ asset('dist/images/error/error-403.png') }}" alt="Not Found">
    <div class="text-center">
        <h1 class="error-title">403 Forbidden</h1>
        <p class="fs-5 text-gray-600">Anda tidak diizinkan untuk melihat halaman ini.</p>
        <a href="{{ Auth::check() ? url('/dashboard') : url('/') }}" class="btn btn-lg btn-outline-primary mt-3">
            {{ Auth::check() ? 'Kembali ke Dashboard' : 'Kembali ke Beranda' }}
        </a>
    </div>
@endsection
