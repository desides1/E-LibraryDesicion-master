@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                Tabel {{ $title }}
            </div>
            <div class="card-body">
                @role('Pustakawan')
                    @if ($data->count() > 0)
                        <a href="{{ url('/book-collection/export-excel') }}"
                            class="btn btn-success icon icon-left fw-bolder mb-3"><i
                                class="fas fa-file-excel me-2"></i>Download</a>
                    @endif
                    {{-- <a href="/book-collection/create" class="btn btn-dark mb-3 me-2 user-create-btn">Tambah Buku</a> --}}
                    {{-- <a href="/book-collection" class="btn btn-dark mb-3 user-create-btn">Sinkronisasi Data</a> --}}
                @endrole

                <table class="table" id="table1">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">No</th>
                            <th>Judul Buku</th>
                            <th>ISBN</th>
                            <th>Penulis</th>
                            <th class="text-center">Tahun Terbit</th>
                            <th>Penerbit</th>
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
                                <td>{{ Str::limit($item->author, 25) }}</td>
                                <td class="text-center">{{ $item->publication_date }}</td>
                                </td>
                                <td>{{ $item->publisher ? $item->publisher : $item->user->name }}</td>
                                <td>
                                    @if ($item->status == 'Aktif' || $item->status == 'Tidak Aktif')
                                        <button
                                            class="btn {{ $item->status == 'Aktif' ? 'btn-success' : 'btn-danger' }} status-container icon icon-left"
                                            id="status-container" data-user-id="{{ $item->id }}"><i
                                                class="fas fa-cogs me-1"></i>
                                            {{ $item->status }}
                                        </button>
                                    @else
                                        <button class="btn btn-primary">{{ $item->status }}</button>
                                    @endif

                                </td>
                                <td>
                                    <a href="{{ route('book-collection.show', encrypt($item->id)) }}"
                                        class="text-decoration-none btn btn-navy icon icon-left text-light user-detail-btn me-1"
                                        data-user-id="{{ $item->id }}"><i
                                            class="fas fa-info-circle me-1"></i>Detail</a>

                                    @role('Pustakawan')
                                        <a href="{{ route('book-collection.edit', encrypt($item->id)) }}"
                                            class="text-decoration-none btn btn-blues icon icon-left text-light user-detail-btn"
                                            data-user-id="{{ $item->id }}"><i class="fas fa-edit me-2"></i>Ubah</a>
                                    @endrole
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    @include('pages.book.dist.styles')
@endpush

@push('scripts')
    <script defer src="{{ asset('dist/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script defer src="{{ asset('dist/js/data_tables.js') }}"></script>
    @include('pages.book.dist.status')
@endpush
