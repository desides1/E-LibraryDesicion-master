@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                Tabel {{ $title }}
            </div>
            <div class="card-body">
                <a href="{{ url('/book-publisher') }}" class="btn btn-outline-dark mb-3 user-create-btn">Kembali</a>
                <table class="table" id="table1">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Judul Buku</th>
                            <th>ISBN</th>
                            <th>Penulis</th>
                            <th class="text-center">Tahun Terbit</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->isbn }}</td>
                                <td>{{ $item->author }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->publication_date)->format('Y') }}
                                </td>
                                <td>{{ $item->category->name }}</td>
                                <td>
                                    <a href="{{ route('detailPublisher', ['id' => encrypt($item->id)]) }}"
                                        class="text-decoration-none btn btn-outline-dark user-detail-btn"
                                        data-user-id="{{ $item->id }}">Detail</a>
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
