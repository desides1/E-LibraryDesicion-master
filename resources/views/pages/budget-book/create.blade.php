@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
            </div>

            <div class="card-body">
                <form class="form form-horizontal" id="formInsert" enctype="multipart/form-data">
                    <div class="mb-3 row">
                        <label for="price" class="col-sm-2 col-form-label">Anggaran Buku</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="price" value="{{ old('price') }}"
                                name="price" required oninput="(() => { restrictNumber(this); })()">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="ppn" class="col-sm-2 col-form-label">PPN Buku</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="ppn" value="{{ old('ppn') }}"
                                name="ppn" required oninput="(() => { restrictNumberPn(this); })()">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="year" class="col-sm-2 col-form-label">Tahun Anggaran Buku</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="year" name="year"
                                value="{{ old('year') }}" required oninput="(() => { restrictNumber(this); })()">
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
    @include('pages.budget-book.dist.styles')
@endpush

@push('scripts')
    @include('pages.budget-book.dist.handler')
@endpush
