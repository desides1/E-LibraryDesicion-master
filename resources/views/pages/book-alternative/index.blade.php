@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                Tabel Data {{ $title }}
            </div>
            <div class="card-body">
                @if ($data->count() > 0)
                    <a href="{{ url('/user-alternative/export-excel') }}"
                        class="btn btn-success icon icon-left fw-bolder mb-3 user-create-btn"><i
                            class="fas fa-file-excel me-2"></i>Download</a>
                @endif

                <form id="filterForm" action="{{ url('/user-alternative') }}" method="GET">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <select class="js-example-basic-single form-select" aria-label="Default select example"
                                name="year">
                                <option value="alltime" {{ $selectedYear === 'alltime' ? 'selected' : '' }}>
                                    Keseluruhan Tahun
                                </option>
                                @foreach ($year as $years)
                                    <option value="{{ $years->year }}"
                                        {{ $selectedYear === $years->year ? 'selected' : '' }}>Tahun {{ $years->year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <select class="js-example-basic-single form-select" aria-label="Default select example"
                                name="status">
                                <option value="allstatus" {{ $selectedStatus === 'allstatus' ? 'selected' : '' }}>
                                    Keseluruhan Status
                                </option>
                                @foreach ($status as $item)
                                    <option value="{{ $item }}" {{ $selectedStatus === $item ? 'selected' : '' }}>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <select class="js-example-basic-single form-select" aria-label="Default select example"
                                name="major">
                                <option value="all" {{ $selectedMajor === 'all' ? 'selected' : '' }}>
                                    Keseluruhan Prodi dan Unit
                                </option>
                                <optgroup label="Program Studi">
                                    @foreach ($major as $majors)
                                        <option value="{{ $majors->name }}"
                                            {{ $selectedMajor === $majors->name ? 'selected' : '' }}>{{ $majors->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Unit">
                                    @foreach ($unit as $item)
                                        <option value="{{ $item->name }}"
                                            {{ $selectedMajor === $item->name ? 'selected' : '' }}>{{ $item->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </form>

                <table class="table" id="table1">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">No</th>
                            <th>Judul Buku</th>
                            {{-- <th>Nomor Identitas</th> --}}
                            <th>Prodi/Unit</th>
                            <th>Status</th>
                            <th>Nama Pemustaka</th>
                            <th>ISBN</th>
                            <th>Penerbit</th>
                            <th class="text-center">Tahun Terbit</th>
                            <th class="text-center">Tanggal Pengajuan</th>
                            <th>Aksi</th>
                            {{-- <th class="text-center">Tahun Pengajuan</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td class="text-start">{{ $loop->iteration }}</td>
                                <td>{{ Str::limit($item->publisher->title, 30) }}</td>
                                {{-- <td>{{ $item->borrowed->number_id }}</td> --}}
                                <td>{{ Str::limit($item->borrowed->major, 25) }}</td>
                                <td>{{ $item->borrowed->status }}</td>
                                <td>{{ Str::limit($item->borrowed->name, 25) }}</td>
                                <td>{{ Str::limit($item->publisher->isbn, 25) }}</td>
                                <td>{{ Str::limit($item->publisher->status == 'Usulan Pemustaka' ? $item->publisher->user->name : $item->publisher->user->name, 25) }}
                                </td>
                                <td class="text-center">
                                    {{ $item->publisher->publication_date }}</td>
                                {{-- <td class="text-center">{{ $item->year }}</td> --}}
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->year)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('user-alternative.show', encrypt($item->id)) }}"
                                        class="text-decoration-none btn btn btn-navy icon icon-left text-light user-detail-btn"
                                        data-user-id="{{ $item->id }}"><i
                                            class="fas fa-info-circle me-1"></i>Detail</a>
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

@include('pages.book-alternative.dist.handler')
@include('pages.book-alternative.dist.styles')
