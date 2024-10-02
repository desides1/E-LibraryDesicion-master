@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <a href="{{ url('/major') }}" class="btn btn-outline-dark user-create-btn">Kembali</a>
            </div>

            <div class="card-body">
                <div class="mb-3 row">
                    <label for="inputName" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName" value="{{ old('name', $data->name) }}"
                            readonly autofocus>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputDepartment" class="col-sm-2 col-form-label">Jurusan</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputDepartment"
                            value="{{ old('department', $data->department) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputStatus" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputStatus"
                            value="{{ old('status', $data->status) }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('pages.major.dist.styles')
@include('pages.major.dist.handler')
