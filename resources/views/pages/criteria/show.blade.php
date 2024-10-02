@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                Tabel Data {{ $title }}
            </div>
            <div class="card-body">
                <a href="{{ url('/weight-criteria') }}" class="btn btn-outline-dark mb-3 user-create-btn">Kembali</a>

                <table class="table" id="table1">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Kriteria</th>
                            <th>Nama Kriteria</th>
                            <th>Jenis Kriteria</th>
                            <th>Bobot Kriteria</th>
                            <th>Sub Kriteria</th>
                            {{-- <th>Keterangan</th> --}}
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $previousCriteria = null; @endphp

                        @foreach ($data as $item)
                            <tr>
                                @if ($item->criteria->code != $previousCriteria)
                                    @foreach (['code', 'name', 'type', 'weight'] as $attribute)
                                        <td rowspan="5">{{ $item->criteria->$attribute }}</td>
                                    @endforeach
                                @endif

                                <td>{{ $item->name_sub }}</td>
                                {{-- <td>{{ $item->description }}</td> --}}
                                <td>{{ $item->value }}</td>
                            </tr>
                            @php $previousCriteria = $item->criteria->code; @endphp
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
