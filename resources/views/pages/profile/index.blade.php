@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="avatar avatar-2xl">
                                <img src="{{ asset('dist/images/faces/2.jpg') }}" class="img-fluid" alt="Avatar"
                                    id="img-profileUser">
                            </div>

                            @foreach ($data as $item)
                                <h3 class="mt-3">{{ $item->name }}</h3>
                                <p class="text-small">{{ $item->roles->pluck('subdistrict')->implode(', ') }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        @foreach ($data as $item)
                            <div class="form-group">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Your Name" value="{{ $item->name }}" onclick="preventInput(this)">
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" name="email" id="email" class="form-control"
                                    placeholder="Your Email" value="{{ $item->email }}" onclick="preventInput(this)">
                            </div>
                            <div class="form-group">
                                <label for="subdistrict" class="form-label">ID Anggota</label>
                                <input type="text" name="subdistrict" id="subdistrict" class="form-control"
                                    placeholder="Your subdistrict" value="{{ $item->number_id }}"
                                    onclick="preventInput(this)">
                            </div>
                            <div class="form-group">
                                <label for="role" class="form-label">Peran</label>
                                <input type="text" name="status" id="role" class="form-control" placeholder=""
                                    value="{{ implode(', ', $item->getRoleNames()->toArray()) }}"
                                    onclick="preventInput(this)">
                            </div>
                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <input type="text" name="status" id="status" class="form-control" placeholder=""
                                    value="{{ implode(', ', $item->getPermissionNames()->toArray()) }}"
                                    onclick="preventInput(this)">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger icon icon-left mt-2" data-bs-toggle="modal"
                                    data-bs-target="#userManageModal"><i class="fas fa-key me-2"></i>Reset Password</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('pages.profile.update')
@endsection

@include('pages.profile.dist.styles')
@include('pages.profile.dist.handler')
