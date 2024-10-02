@extends('layouts.front')

@section('content_fe')
    <div class="container-fluid pb-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-3">
            <div class="row g-5">
                <div class="col-lg-3" style="min-height: 320px;">
                    <div class="position-relative">
                        <img class="position-absolute rounded wow zoomIn image-zoomable" data-wow-delay="0.9s"
                            src="{{ Storage::exists($item->image) ? asset('storage/' . $item->image) : asset('components/img/cover-book.png') . '?v=' . time() }}"
                            style="object-fit: contain; width: 100%; height: 220px;">
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="section-title position-relative pb-3 mb-5">
                        <h1 class="mb-0">{{ $item->title }}</h1>
                    </div>
                    <p class="mb-4" style="text-align: justify;">
                        {{ $item->abstract }}
                    </p>
                    <div class="row g-0 mb-3">
                        <div class="border rounded p-4">
                            <ul class="nav nav-tabs" id="myTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" id="penerbit-tab" data-bs-toggle="tab"
                                        href="#penerbit" role="tab" aria-controls="penerbit"
                                        aria-selected="true">Penerbit</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="penulis-tab" data-bs-toggle="tab" href="#penulis" role="tab"
                                        aria-controls="penulis" aria-selected="false">Penulis</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="kategori-tab" data-bs-toggle="tab" href="#kategori"
                                        role="tab" aria-controls="kategori" aria-selected="false">Kategori Buku</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="isbn-tab" data-bs-toggle="tab" href="#isbn" role="tab"
                                        aria-controls="isbn" aria-selected="false">ISBN</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="stok-tab" data-bs-toggle="tab" href="#stok" role="tab"
                                        aria-controls="stok" aria-selected="false">Stok Buku</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="terbit-tab" data-bs-toggle="tab" href="#terbit" role="tab"
                                        aria-controls="terbit" aria-selected="false">Tahun Terbit</a>
                                </li>
                            </ul>
                            <div class="tab-content mt-3" id="myTabContent">
                                <div class="tab-pane fade show active" id="penerbit" role="tabpanel"
                                    aria-labelledby="penerbit-tab">
                                    {{ $item->publisher ? $item->publisher : $item->user->name }}
                                </div>
                                <div class="tab-pane fade" id="penulis" role="tabpanel" aria-labelledby="penulis-tab">
                                    {{ $item->author }}
                                </div>
                                <div class="tab-pane fade" id="kategori" role="tabpanel" aria-labelledby="kategori-tab">
                                    {{ $item->category->name }}
                                </div>
                                <div class="tab-pane fade" id="isbn" role="tabpanel" aria-labelledby="isbn-tab">
                                    {{ $item->isbn }}
                                </div>
                                <div class="tab-pane fade" id="stok" role="tabpanel" aria-labelledby="stok-tab">
                                    {{ $item->available_stock }}
                                </div>
                                <div class="tab-pane fade" id="terbit" role="tabpanel" aria-labelledby="terbit-tab">
                                    {{ $item->publication_date }}
                                </div>
                            </div>

                        </div>
                    </div>
                    <a href="{{ url('/front-book') }}" class="btn btn-danger py-2 px-4 mt-3 wow zoomIn"
                        data-wow-delay="0.9s">Kembali</a>
                    {{-- <button type="button" class="btn btn-success py-2 px-4 ms-2 mt-3 wow zoomIn"
                        data-wow-delay="0.9s">Usulkan</button> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
