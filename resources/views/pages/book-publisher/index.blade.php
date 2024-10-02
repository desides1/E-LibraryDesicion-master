@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                Tabel {{ $title }}
            </div>
            <div class="card-body">
                <a href="{{ url('/book-list/create') }}"
                    class="btn btn-warning text-dark fw-bolder icon icon-left mb-3 user-create-btn"><i
                        class="fas fa-plus me-2"></i>Tambah Buku</a>

                <a href="{{ url('/book-list/import-excel') }}"
                    class="btn btn-orange text-light fw-bolder icon icon-left mb-3 ms-2 user-import-btn"><i
                        class="fas fa-file-import me-2"></i>Import Buku</a>

                @if ($data->count() > 0)
                    <a href="{{ url('/book-list/export-excel') }}"
                        class="btn btn-success icon icon-left fw-bolder mb-3 ms-2 user-excel-btn"><i
                            class="fas fa-file-excel me-2"></i>Download</a>
                @endif

                <a href="{{ asset('dist/images/Template_Import_Data_Buku.xlsx') }}"
                    download="Template_Import Data Buku.xlsx"
                    class="btn btn-tosca text-light icon icon-left fw-bolder mb-3 ms-2 user-excel-btn"><i
                        class="fas fa-file-download me-2"></i>Template Buku</a>

                <table class="table" id="table1">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">No</th>
                            <th>Nama Buku</th>
                            <th>ISBN</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th class="text-center">Tahun Terbit</th>
                            <th>Harga Buku</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td class="text-start">{{ $loop->iteration }}</td>
                                <td>{{ Str::limit($item->title, 35) }}</td>
                                <td>{{ $item->isbn }}</td>
                                <td>{{ Str::limit($item->author, 35) }}</td>
                                <td>{{ Str::limit($item->publisher, 35) }}</td>
                                <td class="text-center">{{ $item->publication_date }}
                                </td>
                                <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>
                                    <button
                                        class="btn {{ $item->status == 'Aktif' ? 'btn-success text-light' : 'btn-danger' }} status-container icon icon-left"
                                        id="status-container" data-user-id="{{ $item->id }}"><i
                                            class="fas fa-cogs me-1"></i>
                                        {{ $item->status }}
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('book-list.show', encrypt($item->id)) }}"
                                        class="text-decoration-none btn btn-navy icon icon-left text-light user-detail-btn me-1"
                                        data-user-id="{{ $item->id }}"><i
                                            class="fas fa-info-circle me-1"></i>Detail</a>
                                    <a href="{{ route('book-list.edit', encrypt($item->id)) }}"
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

    {{-- modal --}}
    {{-- @include('pages.book-publisher.import_excel') --}}
    {{-- end modal --}}
@endsection

@push('scripts')
    @include('pages.book-publisher.dist.status')
    <script defer src="{{ asset('dist/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script defer src="{{ asset('dist/js/data_tables.js') }}"></script>
@endpush
@include('pages.book-publisher.dist.styles')
