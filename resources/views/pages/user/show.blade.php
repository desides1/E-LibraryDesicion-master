@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <a href="{{ url('/user-management') }}" class="btn btn-outline-dark user-create-btn">Kembali</a>
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
                    <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputEmail" value="{{ old('name', $data->email) }}"
                            readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputNumberId" class="col-sm-2 col-form-label">ID Anggota</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputNumberId"
                            value="{{ old('name', $data->number_id) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputRole" class="col-sm-2 col-form-label">Hak Akses</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputRole"
                            value="{{ old('role', implode(', ', $data->getRoleNames()->toArray())) }}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPermission" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputPermission"
                            value="{{ old('permission', implode(', ', $data->getPermissionNames()->toArray())) }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    @include('pages.user.dist.styles')
@endpush
