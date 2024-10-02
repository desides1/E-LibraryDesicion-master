@extends('layouts.front')

@section('content_fe')
    @if ($data->count() > 0)
        <div class="container-fluid pb-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
                    <h5 class="fw-bold text-primary text-uppercase">Koleksi Terbaru</h5>
                    <h1 class="mb-0">Baca Koleksi Buku Terbaru dari Perpustakaan</h1>
                </div>

                {{-- search --}}
                @include('pages.front.book-collection.search')
                {{-- end search --}}

                <div class="row g-3">
                    @foreach ($data as $item)
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-xs-4 col-6 wow slideInUp" data-wow-delay="0.3s">
                            <div class="card h-100 border rounded-3 overflow-hidden d-flex flex-column" id="card-book"
                                data-id="{{ $item->id }}">
                                <div class="card-img-top text-center position-relative overflow-hidden">
                                    <img class="img-fluid zoom-effect mt-1" loading="lazy"
                                        src="{{ Storage::exists($item->image) ? asset('storage/' . $item->image) : asset('components/img/cover-book.png') . '?v=' . time() }}"
                                        alt="{{ $item->title }}">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2">
                                        <small><i class="far fa-calendar-alt text-primary me-1"></i>
                                            {{ $item->publication_date }}
                                        </small>
                                    </div>
                                    <h6 class="mb-3 fw-bold flex-grow-0 text-center">{{ Str::limit($item->title, 36) }}</h6>
                                    <div class="flex-grow-1 d-flex align-items-end">
                                        <p class="card-text text-truncate w-100" style="text-align: justify;">
                                            {{ Str::limit(strip_tags($item->abstract), 20) }}
                                        </p>
                                    </div>
                                    <a class="fw-bolder mt-2" href="{{ url('/front-book/' . encrypt($item->id)) }}">Baca
                                        selengkapnya</a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- pagination --}}
                    @include('components.pagination')
                    {{-- end pagination --}}

                </div>
            </div>
        </div>
    @else
    @empty($data)
        <div class="container text-center mb-5">
            <p class="text-danger">Maaf, saat ini belum ada koleksi buku yang tersedia di perpustakaan.</p>
        </div>
    @else
        <div class="container text-center mb-5">
            <p class="text-danger">Maaf, data yang Anda cari tidak ditemukan.</p>
            <button class="btn btn-primary mt-2" onclick="window.location.href = '{{ url('/front-book') }}';">Cari
                Kembali</button>
        </div>
    @endempty
@endif
@endsection

@include('pages.front.book-collection.dist.styles')
