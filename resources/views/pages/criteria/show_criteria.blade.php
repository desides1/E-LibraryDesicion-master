@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            @if ($criteria->sub_criterias == 'Iya')
                <div class="card-header">
                    Tabel {{ $title }}
                </div>
            @endif

            <div class="card-body">
                <a href="{{ url('/weight-criteria') }}" class="btn btn-outline-dark mb-3 user-create-btn">Kembali</a>

                @if ($criteria->sub_criterias == 'Iya')
                    <table class="table" id="table1">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Kriteria</th>
                                <th>Nama Kriteria</th>
                                <th>Jenis Kriteria</th>
                                <th>Bobot Kriteria</th>
                                <th>Sub Kriteria</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->criteria->code }}</td>
                                    <td>{{ $item->criteria->name }}</td>
                                    <td>{{ $item->criteria->type }}</td>
                                    <td>{{ $item->criteria->weight }}</td>
                                    <td>{{ $item->name_sub }}</td>
                                    <td>{{ $item->value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    @foreach (['Kode Kriteria' => 'code', 'Nama Kriteria' => 'name', 'Jenis Kriteria' => 'type', 'Bobot Kriteria' => 'weight', 'Status Kriteria' => 'status'] as $label => $field)
                        <div class="mb-3 row">
                            <label for="{{ $field }}" class="col-sm-2 col-form-label">{{ $label }}</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="{{ $field }}"
                                    value="{{ old($field, $criteria->$field) }}" autofocus name="{{ $field }}"
                                    required readonly oninput="(() => { restrictInput(this); })()"
                                    placeholder="Masukkan {{ $label }}">
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script defer src="{{ asset('dist/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script defer src="{{ asset('dist/js/data_tables.js') }}"></script>
@endpush

@push('styles')
    @include('pages.criteria.dist.styles')
@endpush
