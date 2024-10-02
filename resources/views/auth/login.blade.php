@extends('layouts.auth')

@section('auth_content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        {{-- logo --}}
                        <div class="app-brand justify-content-center px-3">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <img src="{{ asset('dist/images/logo/logo-poliwangi.png') }}"
                                        alt="" srcset="" style="width: 75px">
                                </span>
                            </a>
                            <span class="app-brand-text demo text-body fw-bolder">Sistem Usulan Buku Pustaka</span>
                        </div>
                        {{-- end logo --}}

                        <h4 class="mb-2">Selamat Datang! 👋</h4>
                        <p class="mb-4 text-justify" style="text-align: justify;">Silakan masuk ke akun Anda yang telah
                            didaftarkan.</p>

                        @if (session('error'))
                            <div id="customAlert" class="alert alert-danger alert-dismissible" style="color: black;"
                                role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @include('auth.login-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .error-message {
            text-align: justify;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.getElementById('remember-me').addEventListener('click', (event) => {
            event.preventDefault();
        });

        const hideAlert = () => {
            setTimeout(() => {
                const alert = document.getElementById('customAlert');
                if (alert) {
                    alert.remove();
                }
            }, 10000);
        };

        window.onload = hideAlert;

        const validateInput = (inputElement) => {
            const inputValue = inputElement.value;

            if (/\s/.test(inputValue)) {
                inputElement.value = inputValue.replace(/\s/g, '');
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formAuthentication');
            const btnMasuk = document.getElementById('btnMasuk');

            form.addEventListener('submit', () => {
                btnMasuk.innerHTML = 'Loading...';
                btnMasuk.disabled = true;
            });
        });
    </script>
@endpush
