@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
            </div>

            <div class="card-body">
                <form class="form form-horizontal" id="formInsert" enctype="multipart/form-data">
                    <div class="mb-3 row">
                        <label for="name" class="col-sm-2 col-form-label">Program Studi</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" value="{{ old('name') }}" autofocus
                                name="name" required oninput="(() => { restrictInput(this); })()">

                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="department" class="col-sm-2 col-form-label">Jurusan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="department" value="{{ old('department') }}"
                                name="department" required oninput="(() => { restrictInput(this); })()">
                        </div>
                    </div>
                    <div class="col-sm-12 d-flex justify-content-end mt-3">
                        <button type="button" id="cancelButton" class="btn btn-outline-dark me-2 mb-1"
                            onclick="location.href='{{ url('/major') }}'">Batal</button>
                        <button type="submit" class="btn btn-outline-primary mb-1">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@include('pages.major.dist.styles')
@include('pages.major.dist.handler')
