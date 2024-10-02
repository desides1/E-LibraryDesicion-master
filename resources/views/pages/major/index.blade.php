@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                Tabel {{ $title }}
            </div>
            <div class="card-body">
                <a href="{{ url('/major/create') }}"
                    class="btn btn-warning text-dark fw-bolder icon icon-left mb-3 user-create-btn"><i
                        class="fas fa-plus me-2"></i>Tambah Program Studi</a>

                <table class="table" id="table1">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">No</th>
                            <th>Program Studi</th>
                            <th>Jurusan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td class="text-start">{{ $loop->iteration }}</td>
                                <td>{{ Str::limit($item->name, 35) }}</td>
                                <td>{{ Str::limit($item->department, 35) }}</td>
                                <td>
                                    <button
                                        class="btn {{ $item->status == 'Aktif' ? 'btn-success' : 'btn-danger' }} status-container icon icon-left"
                                        id="status-container" data-user-id="{{ $item->id }}"><i
                                            class="fas fa-cogs me-1"></i>
                                        {{ $item->status }}
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('major.show', encrypt($item->id)) }}"
                                        class="text-decoration-none btn btn-navy icon icon-left text-light user-detail-btn me-1"
                                        data-user-id="{{ $item->id }}"><i class="fas fa-info-circle me-1"></i>Detail</a>
                                    <a href="{{ route('major.edit', encrypt($item->id)) }}"
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
@endsection

@push('scripts')
    <script defer src="{{ asset('dist/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script defer src="{{ asset('dist/js/data_tables.js') }}"></script>
@endpush

@include('pages.major.dist.handler')
@include('pages.major.dist.styles')
