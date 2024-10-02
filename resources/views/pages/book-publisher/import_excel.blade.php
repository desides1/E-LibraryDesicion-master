@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
            </div>

            <div class="card-body">
                <form class="form form-horizontal" id="formInsert" enctype="multipart/form-data">
                    <div class="mb-3 row">
                        <label for="title" class="col-sm-2 col-form-label">File Excel Buku</label>
                        <div class="col-sm-10">
                            <input id="importExcel" type="file" class="form-control mt-2" name="importexcel"
                                accept=".xlsx" placeholder="Masukkan File Excel Buku" value="{{ old('importexcel') }}"
                                required>
                        </div>
                    </div>

                    <div id="excelData" class="mt-4">
                    </div>

                    <div class="col-sm-12 d-flex justify-content-end mt-3">
                        <button type="button" id="cancelButton" class="btn btn-outline-dark me-2 mb-1"
                            onclick="location.href='/book-list'">Batal</button>
                        <button type="submit" class="btn btn-outline-primary mb-1">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <script defer type="text/javascript" src="{{ asset('dist/js/xlsx.full.min.js') }}"></script>
@endpush

@push('scripts')
    <script defer src="{{ asset('dist/vendors/simple-datatables/simple-datatables.js') }}"></script>
    @include('pages.book-publisher.dist.excel_handle')
@endpush
