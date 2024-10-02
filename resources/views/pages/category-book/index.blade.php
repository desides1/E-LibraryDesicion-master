@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                Tabel Data {{ $title }}
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-warning text-dark fw-bolder icon icon-left mb-3 user-create-btn"
                    data-bs-toggle="modal" data-bs-target="#userCreateModal"><i class="fas fa-plus me-2"></i>Tambah
                    {{ $title }}</button>

                <table class="table" id="table1">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">No</th>
                            <th>Kode Klasifikasi Buku</th>
                            <th>Nama Klasifikasi Buku</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td class="text-start">{{ $loop->iteration }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <a href="{{ route('book-classification.show', encrypt($item->id)) }}"
                                        class="text-decoration-none btn btn-navy icon icon-left text-light user-detail-btn me-1"
                                        data-user-id="{{ $item->id }}"><i class="fas fa-info-circle me-1"></i>
                                        Detail</a>
                                    <a href="{{ route('book-classification.edit', encrypt($item->id)) }}"
                                        class="text-decoration-none btn btn-blues icon icon-left text-light user-detail-btn"
                                        data-user-id="{{ $item->id }}"><i class="fas fa-edit me-2"></i>Ubah</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    {{-- @include('pages.category-book.update') --}}
    @include('pages.category-book.create')
@endsection

@push('styles')
    @include('pages.category-book.dist.styles')
@endpush

@push('scripts')
    <script defer src="{{ asset('dist/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script defer src="{{ asset('dist/js/data_tables.js') }}"></script>
    @include('pages.category-book.dist.handler')
@endpush
