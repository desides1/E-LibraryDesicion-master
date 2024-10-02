@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                Tabel Data {{ $title }}
            </div>
            <div class="card-body">
                @role('Pustakawan')
                    <a href="{{ url('/book-budget/create') }}"
                        class="btn btn-warning text-dark fw-bolder icon icon-left mb-3 user-create-btn"><i
                            class="fas fa-plus me-2"></i>Tambah Anggaran Buku</a>
                @endrole

                <table class="table" id="table1">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">No</th>
                            <th>Anggaran</th>
                            <th class="text-center">PPN</th>
                            <th class="text-center">Tahun</th>
                            <th class="text-start">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td class="text-start">{{ $loop->iteration }}</td>
                                <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $item->ppn }}%</td>
                                <td class="text-center">{{ $item->year }}</td>
                                <td class="text-start">
                                    @role('Pustakawan')
                                        <a href="{{ route('book-budget.edit', encrypt($item->id)) }}"
                                            class="text-decoration-none btn btn-navy icon icon-left text-light user-edit-role-btn me-1"
                                            data-user-id="{{ $item->id }}"><i class="fas fa-edit me-2"></i>Ubah</a>
                                        <a href="{{ route('book-budget.show', encrypt($item->id)) }}"
                                            class="text-decoration-none btn btn-blues icon icon-left text-light user-edit-role-btn me-1"
                                            data-user-id="{{ $item->id }}"><i
                                                class="fas fa-book-reader me-2"></i>Rekomendasi</a>
                                        <a href="{{ route('book-budget.history', encrypt($item->id)) }}"
                                            class="text-decoration-none btn btn-light-blue icon icon-left text-light user-edit-role-btn"
                                            data-user-id="{{ $item->id }}"><i class="fas fa-history me-2"></i>Riwayat</a>
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

@include('pages.budget-book.dist.styles')

@push('scripts')
    <script defer src="{{ asset('dist/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script defer src="{{ asset('dist/js/data_tables.js') }}"></script>
@endpush
