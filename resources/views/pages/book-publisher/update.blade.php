@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
            </div>

            <div class="card-body">
                <form class="form form-horizontal" id="formUpdate" enctype="multipart/form-data" method="PATCH"
                    action="{{ route('book-list.update', $data->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 row">
                        <label for="title" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="title"
                                value="{{ old('title', $data->title) }}" autofocus name="title" required>

                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="isbn" class="col-sm-2 col-form-label">ISBN</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="isbn" value="{{ old('isbn', $data->isbn) }}"
                                name="isbn" required oninput="(() => { restrictNumbers(this); })()">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="author" class="col-sm-2 col-form-label">Penulis</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="author"
                                value="{{ old('author', $data->author) }}" name="author" required
                                oninput="(() => { restrictInput(this); })()">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="author" class="col-sm-2 col-form-label">Penerbit</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="publishers" value="{{ old('publisher', $data->publisher) }}"
                                name="publisher" required oninput="(() => { restrictInput(this); })()">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="publication_date" class="col-sm-2 col-form-label">Tahun Terbit</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="publication_date" name="publication_date"
                                value="{{ old('publication_date', $data->publication_date) }}" required
                                oninput="(() => { restrictNumberPn(this); })()">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="category_id" class="col-sm-2 col-form-label">Klasifikasi Buku</label>
                        <div class="col-sm-10">
                            <select class="choices form-select" name="category_id" id="category_id" required>
                                <option disabled>Pilih jenis Klasifikasi buku</option>
                                @foreach ($category as $item)
                                    <option value="{{ $item->id }}" @if (old('category_id', $data->category_id) == $item->id) selected @endif>
                                        {{ $item->name }} ({{ $item->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="price" class="col-sm-2 col-form-label">Harga</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="price"
                                value="{{ old('price', $data->price) }}" name="price" required
                                oninput="(() => { restrictNumber(this); })()">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="available_stock" class="col-sm-2 col-form-label">Stok</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="available_stock"
                                value="{{ old('available_stock', $data->available_stock) }}" name="available_stock" required
                                oninput="(() => { restrictNumber(this); })()">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="type_book" class="col-sm-2 col-form-label">Jenis Buku</label>
                        <div class="col-sm-10 d-flex">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_book" id="flexRadioDefault1"
                                    value="E-Book" {{ old('type_book', $data->type_book) == 'E-Book' ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    E-Book
                                </label>
                            </div>
                            <div class="form-check ms-3">
                                <input class="form-check-input" type="radio" name="type_book" id="flexRadioDefault2"
                                    value="Cetak" {{ old('type_book', $data->type_book) == 'Cetak' ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Cetak
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="abstract" class="col-sm-2 col-form-label">Abstract</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="abstract"
                                value="{{ old('abstract', $data->abstract) }}" name="abstract" required
                                oninput="(() => { restrictInput(this); })()">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputImage" class="col-sm-2 col-form-label">Cover Buku</label>
                        <div class="col-sm-10">
                            <input type="file" accept=".jpg, .jpeg, .png" onchange="previewImage()"
                                class="form-control mb-3" id="inputImage" value="{{ old('image', $data->image) }}"
                                name="image" style="display: none;">
                            <div class="image-preview-container">
                                <button type="button" onclick="resetImage()" class="btn btn-secondary"
                                    id="resetButton">Ganti Gambar</button>
                                <img class="img-preview img-fluid col-sm-7" id="imagePreview">
                                @if (Storage::exists($data->image))
                                    <img class="img-preview col-sm-7 my-3" src="{{ asset('storage/' . $data->image) }}"
                                        id="imagePreviewDefault" style="width: 200px; height: 300px;">
                                @else
                                    <p class="form-control mt-3" id="previewCondition">Gambar tidak ditemukan dalam
                                        direktori
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 d-flex justify-content-end mt-3">
                        <button type="button" id="cancelButton" class="btn btn-outline-dark me-2 mb-1">Batal</button>
                        <button type="submit" class="btn btn-outline-primary mb-1">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    @include('pages.book-publisher.dist.styles')
@endpush

@push('scripts')
    @include('pages.book-publisher.dist.handler')
    @include('pages.book-publisher.dist.image_update')
@endpush
