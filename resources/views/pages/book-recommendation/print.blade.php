@extends('layouts.print')

@section('content_print')
    <div class="card-title mb-4 text-center fw-bold text-dark">Rekomendasi Pengadaan Buku Tahun {{ $budget->year }} Anggaran
        Rp{{ number_format($budget->price, 0, ',', '.') }}</div>
    <table class="table table-bordered" id="table5">
        <thead class="table-light">
            <tr>
                <th>Alternatif</th>
                <th class="text-center">Nilai</th>
                <th class="text-center">Peringkat</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rank as $item)
                <tr>
                    <td>{{ $item['title'] }}</td>
                    <td class="text-center">{{ $item['yiValue'] }}</td>
                    <td class="text-center">{{ $item['rank'] }}</td>
                    <td class="text-center">{{ $item['status'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
