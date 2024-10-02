@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <a href="{{ url('/book-list') }}" class="btn btn-outline-dark user-create-btn">Kembali</a>
            </div>

            <div class="card-body">
                <div class="mb-3 row">
                    <label for="inputName" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName" value="{{ old('title', $data->title) }}"
                            readonly autofocus>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputEmail" class="col-sm-2 col-form-label">ISBN</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputEmail" value="{{ old('isbn', $data->isbn) }}"
                            readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputNumberId" class="col-sm-2 col-form-label">Penulis</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputNumberId"
                            value="{{ old('name', $data->author) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPubId" class="col-sm-2 col-form-label">Penerbit</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputPubId"
                            value="{{ old('publisher', $data->publisher) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputRole" class="col-sm-2 col-form-label">Tahun Terbit</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputRole"
                            value="{{ old('price', $data->publication_date) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputCategory" class="col-sm-2 col-form-label">Klasifikasi Buku</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputCategory"
                            value="{{ old('category', $data->category->name) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPrice" class="col-sm-2 col-form-label">Harga</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputPrice"
                            value="{{ old('price', 'Rp' . number_format($data->price, 0, ',', '.')) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputStock" class="col-sm-2 col-form-label">Stok</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputStock"
                            value="{{ old('stock', $data->available_stock) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputTypeBook" class="col-sm-2 col-form-label">Jenis Buku</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputTypeBook"
                            value="{{ old('type_book', $data->type_book) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputAbs" class="col-sm-2 col-form-label">Abstract</label>
                    <div class="col-sm-10">
                        <textarea style="text-align: justify;" class="form-control" id="inputAbs" oninput="autoSize(this)" readonly>{{ old('abstract', $data->abstract) }}</textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputName" class="col-sm-2 col-form-label">Cover Buku</label>
                    <div class="col-sm-10">
                        <img src="{{ Storage::exists($data->image) ? asset('storage/' . $data->image) : asset('components/img/cover-book.png') . '?v=' . time() }}"
                            alt="{{ $data->title }}" style="width: 200px; height: 300px">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    @include('pages.book-publisher.dist.styles')
@endpush

@push('scripts')
    @include('pages.book-publisher.dist.handler')
@endpush
