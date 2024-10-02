@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                Tabel Data {{ $title }}
            </div>
            <div class="card-body">
                @if ($data->count() > 0)
                    <a href="{{ url('/book-publisher/export-excel') }}"
                        class="btn btn-success icon icon-left fw-bolder mb-3 user-create-btn"><i
                            class="fas fa-file-excel me-2"></i>Download</a>
                @endif

                <form id="filterForm" action="{{ url('/book-publisher') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <select class="js-example-basic-single form-select" aria-label="Default select example"
                                name="publisher">
                                <option value="all" {{ $selectedPublisher === 'all' ? 'selected' : '' }}>
                                    Keseluruhan Penerbit
                                </option>
                                @foreach ($user as $users)
                                    <option value="{{ $users->name }}"
                                        {{ $selectedPublisher === $users->name ? 'selected' : '' }}>{{ $users->name }}
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
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th class="text-center">Tahun Terbit</th>
                            <th>Kategori</th>
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
                                <td>{{ Str::limit($item->user->name, 25) }}</td>
                                <td class="text-center">{{ $item->publication_date }}
                                </td>
                                <td>{{ $item->category->name }}</td>
                                <td>
                                    <a href="{{ route('detailPublisher', ['id' => encrypt($item->id)]) }}"
                                        class="text-decoration-none btn btn-navy icon icon-left text-light user-detail-btn"
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

@include('pages.publisher.dist.styles')
@include('pages.publisher.dist.handler')

@push('scripts')
    <script defer src="{{ asset('dist/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script defer src="{{ asset('dist/js/data_tables.js') }}"></script>
@endpush
