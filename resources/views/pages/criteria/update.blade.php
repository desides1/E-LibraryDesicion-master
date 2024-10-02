@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">

            <div class="card-body">
                <form class="form form-horizontal" id="formUpdate" enctype="multipart/form-data" method="PATCH"
                    action="{{ route('weight-criteria.update', $data->id) }}">
                    @csrf
                    @method('PUT')
                    <h5 class="fw-bolder text-dark my-2">A. Data Kriteria</h5>
                    <div class="mb-3 row">
                        <label for="name" class="col-sm-2 col-form-label">Nama Kriteria</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" value="{{ old('name', $data->name) }}"
                                autofocus name="name" required oninput="(() => { restrictInput(this); })()"
                                placeholder="Masukkan Nama Kriteria"
                                {{ $data->name === 'Program Studi' ? 'readonly' : '' }}>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="weight" class="col-sm-2 col-form-label">Bobot Kriteria</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="weight"
                                value="{{ old('weight', $data->weight * 100) }}" name="weight" required
                                oninput="(() => { restrictNumber(this); })()"
                                placeholder="Masukkan Bobot Kriteria Dalam Bentuk Persentase">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="type" class="col-sm-2 col-form-label">Jenis Kriteria</label>
                        <div class="col-sm-10 d-flex">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="flexRadioDefault1"
                                    value="Benefit" required {{ old('type', $data->type) == 'Benefit' ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Benefit
                                </label>
                            </div>
                            <div class="form-check ms-3">
                                <input class="form-check-input" type="radio" name="type" id="flexRadioDefault2"
                                    value="Cost" required {{ old('type', $data->type) == 'Cost' ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Cost
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="sub_criterias" class="col-sm-2 col-form-label">Sub Kriteria</label>
                        <div class="col-sm-10 d-flex">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sub_criterias" id="flexRadioDefault3"
                                    value="Iya"
                                    {{ old('sub_criterias', $data->sub_criterias) == 'Iya' ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexRadioDefault3">
                                    Iya
                                </label>
                            </div>
                            <div class="form-check ms-3">
                                <input class="form-check-input" type="radio" name="sub_criterias" id="flexRadioDefault4"
                                    value="Tidak" required
                                    {{ old('sub_criterias', $data->sub_criterias) == 'Tidak' ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexRadioDefault4">
                                    Tidak
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="subCriteriaContent"
                        style="display: {{ old('sub_criterias', $data->sub_criterias) == 'Iya' ? '' : 'none' }}">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="fw-bolder text-dark">B. Data Sub Kriteria</h5>
                            <button class="btn btn-warning text-dark fw-bolder" id="addSubCriteria"
                                type="button">Tambah</button>
                        </div>
                        <div id="subCriteriaContainer">
                            @if ($sub_criteria->count() > 0)
                                @foreach ($sub_criteria as $item)
                                    <div class="mb-3 row subcriteria">
                                        <label for="subcriteria" class="col-sm-2 col-form-label">Sub Kriteria
                                            {{ $loop->iteration }}</label>
                                        <div class="col-sm-9 subcriteriaDisplay">
                                            <div class="mb-3 row">
                                                <label for="name_sub" class="col-sm-2 col-form-label">Nama Sub
                                                    Kriteria</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="name_sub"
                                                        value="{{ old('name_sub', $item->name_sub) }}" name="name_sub[]"
                                                        placeholder="Masukkan Nama Sub Kriteria">
                                                    <input type="hidden" name="id_sub[]" value="{{ $item->id }}">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="value" class="col-sm-2 col-form-label">Nilai Sub
                                                    Kriteria</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="value"
                                                        value="{{ old('value', $item->value * 100) }}" name="value[]"
                                                        oninput="(() => { restrictNumber(this); })()"
                                                        placeholder="Masukkan Nilai Sub Kriteria Dalam Bentuk Persentase">
                                                </div>
                                            </div>
                                        </div>
                                        <label for="subcriteria"
                                            class="col-sm-1 col-form-label text-danger fw-bolder text-end fs-6"
                                            id="deleteButton" onclick="deleteSubCriteria(this)">Hapus</label>
                                    </div>
                                @endforeach
                            @else
                                <div class="mb-3 row subcriteria">
                                    <label for="subcriteria" class="col-sm-2 col-form-label">Sub Kriteria 1</label>
                                    <div class="col-sm-9 subcriteriaDisplay">
                                        <div class="mb-3 row">
                                            <label for="name_sub" class="col-sm-2 col-form-label">Nama Sub
                                                Kriteria</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="name_sub"
                                                    value="{{ old('name_sub') }}" name="name_sub[]"
                                                    placeholder="Masukkan Nama Sub Kriteria">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="value" class="col-sm-2 col-form-label">Nilai Sub
                                                Kriteria</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="value"
                                                    value="{{ old('value') }}" name="value[]"
                                                    oninput="(() => { restrictNumber(this); })()"
                                                    placeholder="Masukkan Nilai Sub Kriteria Dalam Bentuk Persentase">
                                            </div>
                                        </div>
                                    </div>
                                    <label for="subcriteria"
                                        class="col-sm-1 col-form-label text-danger fw-bolder text-end fs-6"
                                        id="deleteButton" onclick="deleteSubCriteria(this)">Hapus</label>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12 d-flex justify-content-end mt-3">
                        <button type="button" id="cancelButton" class="btn btn-outline-dark me-2 mb-1"
                            onclick="location.href='{{ url('/weight-criteria') }}'">Batal</button>
                        <button type="submit" class="btn btn-outline-primary mb-1">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@include('pages.criteria.dist.handler')

@push('styles')
    <style>
        .form-control:disabled,
        .form-control[readonly] {
            background-color: transparent !important;
        }
    </style>
@endpush
