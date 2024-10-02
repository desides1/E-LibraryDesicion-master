@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <a href="{{ url('/user-alternative') }}" class="btn btn-outline-dark user-create-btn">Kembali</a>
            </div>

            <div class="card-body">
                {{-- identity user --}}
                <h5 class="fw-bolder text-dark my-2">A. Identitas Pemustaka</h5>
                <div class="mb-3 row">
                    <label for="inputNames" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputNames"
                            value="{{ old('name', $item->borrowed->name) }}" readonly autofocus>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputNim"
                        class="col-sm-2 col-form-label">{{ $item->borrowed->status == 'Mahasiswa' ? 'NIM' : 'NIP./NIPPPK./NIK.' }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputNim"
                            value="{{ old('number_id', $item->borrowed->number_id) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputMajor" class="col-sm-2 col-form-label">Program Studi</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputMajor"
                            value="{{ old('major', $item->borrowed->major) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputMajorStatus" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputMajorStatus"
                            value="{{ old('status', $item->borrowed->status) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputMajorYear" class="col-sm-2 col-form-label">Tanggal Pengajuan</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputMajorYear"
                            value="{{ old('year', \Carbon\Carbon::parse($item->year)->format('d/m/Y')) }}" readonly>
                    </div>
                </div>
                {{-- end identity user --}}

                {{-- request book --}}
                <h5 class="fw-bolder text-dark mb-2 mt-4">B. Data Usulan Buku</h5>
                <div class="mb-3 row">
                    <label for="inputName" class="col-sm-2 col-form-label">Judul Buku</label>
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
                    <label for="inputRole" class="col-sm-2 col-form-label">Tahun Terbit</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputRole"
                            value="{{ old('price', $data->publication_date) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPermission" class="col-sm-2 col-form-label">Penerbit</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputPermission"
                            value="{{ old('publisher', $data->publisher ? $data->publisher : $data->user->name) }}"
                            readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="Category" class="col-sm-2 col-form-label">Kategori</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="Category"
                            value="{{ old('category', $data->category->name . ' (' . $data->category->code . ')') }}"
                            readonly>
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
                            value="{{ old('available_stock', $data->available_stock) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputTypeBook" class="col-sm-2 col-form-label">Jenis Buku</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputTypeBook"
                            value="{{ old('type_book', $data->type_book) }}" readonly>
                    </div>
                </div>
                @if ($data->status == 'Aktif' || $data->status == 'Tidak Aktif')
                    <div class="mb-3 row">
                        <label for="inputName" class="col-sm-2 col-form-label">Cover Buku</label>
                        <div class="col-sm-10">
                            <img src="{{ Storage::exists($data->image) ? asset('storage/' . $data->image) : asset('components/img/cover-book.png') . '?v=' . time() }}"
                                alt="{{ $data->title }}" style="width: 200px; height: 300px">
                        </div>
                    </div>
                @else
                    <div class="mb-3 row">
                        <label for="inputStatus" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputStatus"
                                value="{{ old('status', $data->status) }}" readonly>
                        </div>
                    </div>
                @endif
                {{-- end request book --}}
            </div>
        </div>
    </section>
@endsection

@include('pages.book-alternative.dist.handler')
@include('pages.book-alternative.dist.styles')
