@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header fw-bolder">
                Pengajuan Pengadaan Buku Pada Perpustakaan Politeknik Negeri Banyuwangi
            </div>

            <div class="card-body">
                @if ($submit)
                    <p class="text-dark fw-bolder" style="text-align: justify;">Pustakawan, pengajuan pengadaan buku tahunan
                        Anda untuk tahun
                        {{ date('Y') }} ini telah
                        tercatat. Untuk tahun ini,
                        tidak dapat melakukan pengajuan ulang pengadaan buku. Anda dapat melihat riwayat pengadaan buku pada
                        menu <a href="/book-history" class="text-primary">Riwayat Pengadaan</a>.</p>
                @else
                    @include('pages.management-book.payment')
                @endif
            </div>
        </div>
    </section>
@endsection

@include('pages.management-book.dist.styles')

@push('scripts')
    @include('pages.management-book.dist.handler')
@endpush
