@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                Tabel {{ $title }}
            </div>
            <div class="card-body">
                <table class="table" id="table1">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Judul Buku</th>
                            <th>ISBN</th>
                            <th>Jumlah Buku</th>
                            <th class="text-center">Waktu Pengadaan</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->book->title }}</td>
                                <td>{{ $item->book->isbn }}</td>
                                <td>{{ $item->book_quantity }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->purchase_date)->format('d/m/Y') }}
                                </td>
                                <td>{{ in_array($item->book->title, $validTitles) ? 'Pembaharuan Koleksi Buku' : 'Peningkatan Jumlah Stok Buku' }}
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
