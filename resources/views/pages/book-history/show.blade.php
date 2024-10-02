@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                Tabel {{ $title }}
            </div>
            <div class="card-body">
                <a href="{{ url('/book-budget') }}" class="btn btn-outline-dark user-create-btn mb-3">Kembali</a>

                <form id="filterForm" action="{{ route('book-budget.history', encrypt($budget->id)) }}" method="GET">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <select class="js-example-basic-single" aria-label="Default select example" name="major">
                                <option value="all" {{ $selectedMajor === 'all' ? 'selected' : '' }}>
                                    Keseluruhan Prodi
                                </option>
                                @foreach ($major as $majors)
                                    <option value="{{ $majors->name }}"
                                        {{ $selectedMajor === $majors->name ? 'selected' : '' }}>{{ $majors->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <table class="table" id="table1">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">No</th>
                            <th>Judul Buku</th>
                            <th>ISBN</th>
                            <th class="text-center">Status Pengadaan</th>
                            <th>Jumlah Buku</th>
                            <th>Harga Buku</th>
                            <th>Total Harga</th>
                            @if ($selectedMajor !== 'all')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $publisherTotals = [];
                        @endphp

                        @foreach ($data as $item)
                            @if (!array_key_exists($item->publisher_id, $publisherTotals))
                                @php
                                    $publisherTotals[$item->publisher_id] = [
                                        'id' => $item->id,
                                        'publisher_id' => $item->publisher_id,
                                        'book_quantity' => $item->book_quantity,
                                        'book_result' => $item->book_result,
                                        'title' => $item->publisher->title,
                                        'isbn' => $item->publisher->isbn,
                                        'price' => $item->publisher->price,
                                        'status' => $item->status,
                                    ];
                                @endphp
                            @else
                                @php
                                    $publisherTotals[$item->publisher_id]['book_quantity'] += $item->book_quantity;
                                    $publisherTotals[$item->publisher_id]['book_result'] += $item->book_result;
                                @endphp
                            @endif
                        @endforeach

                        @foreach ($publisherTotals as $publisherId => $totals)
                            <tr>
                                <td class="text-start">{{ $loop->iteration }}</td>
                                <td>{{ $totals['title'] }}</td>
                                <td>{{ $totals['isbn'] }}</td>
                                <td class="text-center">
                                    @if ($selectedMajor !== 'all')
                                        @if ($totals['status'] == 'Terealisasi')
                                            <button type="button" class="btn btn-success" id="realization"
                                                data-user-id="{{ $totals['id'] }}"><i
                                                    class="fas fa-cogs me-2"></i>{{ $totals['status'] }}</button>
                                        @else
                                            <button type="button" class="btn btn-primary" id="process"
                                                data-user-id="{{ $totals['id'] }}"><i
                                                    class="fas fa-cogs me-2"></i>{{ $totals['status'] }}</button>
                                        @endif
                                    @else
                                        {{ $totals['status'] }}
                                    @endif
                                </td>
                                <td>{{ $totals['book_quantity'] }}</td>
                                <td>Rp{{ number_format($totals['price'], 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($totals['book_result'], 0, ',', '.') }}</td>
                                @if ($selectedMajor !== 'all')
                                    <td>
                                        <form id="formDelete" method="post"
                                            action="{{ route('book-budget.destroy', $totals['id']) }}">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $totals['id'] }}">
                                            <button class=" btn btn-danger icon icon-left deleteButton"
                                                onclick="confirm?"><i class="fas fa-trash-alt me-2"></i>Hapus</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach

                        @if ($data->isNotEmpty())
                            <tr>
                                <td colspan="6" class="text-end fw-bold text-dark">Total Biaya Pengadaan</td>
                                <td colspan="1" class="fw-bold text-dark" id="result-price">
                                    {{ 'Rp' . number_format($payment_book, 0, ',', '.') }}</td>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end fw-bold text-dark">Total PPN Biaya Pengadaan</td>
                                <td colspan="1" class="fw-bold text-dark" id="result-price">
                                    {{ 'Rp' . number_format($ppn_payment, 0, ',', '.') }}</td>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end fw-bold text-dark">Total Anggaran Pengadaan</td>
                                <td colspan="1" class="fw-bold text-dark" id="result-price">
                                    {{ 'Rp' . number_format($selectedMajor === 'all' ? $budget->price : $budgetProdi, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end fw-bold text-dark">Sisa Anggaran Pengadaan</td>
                                <td colspan="1" class="fw-bold text-dark" id="result-price">
                                    {{ ($selectedMajor === 'all' ? $budget->price : $budgetProdi) - $ppn_payment < 0 ? '-' : '' }}Rp{{ number_format(abs(($selectedMajor === 'all' ? $budget->price : $budgetProdi) - $ppn_payment), 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script defer src="{{ asset('dist/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script defer src="{{ asset('dist/js/data_tables.js') }}"></script>
    @include('pages.book-history.dist.handler')
@endpush

@include('pages.book-history.dist.select2')
@include('pages.book-history.dist.styles')
