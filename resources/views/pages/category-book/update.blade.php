@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
            </div>

            <div class="card-body">
                <form class="form form-horizontal" id="formUpdate" enctype="multipart/form-data" method="PATCH"
                    action="{{ route('book-classification.update', $data->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 row">
                        <label for="title" class="col-sm-2 col-form-label">Kode Klasifikasi Buku</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="title" value="{{ old('code', $data->code) }}"
                                autofocus name="code" required oninput="(() => { validateSpaceInput(this); })()">

                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="isbn" class="col-sm-2 col-form-label">Nama Klasifikasi Buku</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="isbn" value="{{ old('name', $data->name) }}"
                                name="name" required oninput="(() => { restrictInput(this); })()">
                        </div>
                    </div>
                    <div class="col-sm-12 d-flex justify-content-end mt-3">
                        <button type="button" id="cancelButton" class="btn btn-outline-dark me-2 mb-1" >Batal</button>
                        <button type="submit" class="btn btn-outline-primary mb-1">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @include('pages.category-book.dist.handler')
    @include('pages.category-book.dist.button')
@endpush
