@extends('layouts.print')

@section('content_print')
    <div class="card-title text-center fw-bold text-dark">Rekomendasi Pengadaan Buku Anggaran
        Rp{{ number_format($selectedMajor === 'all' ? $budget->price : $budgetProdi, 0, ',', '.') }}</div>
    <div class="card-title mb-4 text-center fw-bold text-dark">
        {{ $selectedMajor === 'all' ? 'Keseluruhan Program Studi' : 'Program Studi ' . $selectedMajor }}
        || Metode {{ $selectedRecom }}</div>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Judul Buku</th>
                <th class="text-start">Nilai</th>
                <th class="text-start">Peringkat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rank as $item)
                <tr>
                    <td>{{ $item['title'] }}</td>
                    @if ($selectedRecom === 'moora')
                        <td class="text-start">{{ number_format($item['yiValue'], 3) }}</td>
                    @elseif ($selectedRecom === 'saw')
                        <td class="text-start">{{ number_format($item['viValue'], 3) }}</td>
                    @elseif ($selectedRecom === 'topsis')
                        <td class="text-start">{{ number_format($item['viValueTopsis'], 3) }}</td>
                    @elseif ($selectedRecom === 'wpm')
                        <td class="text-start">{{ number_format($item['viValue'], 3) }}</td>
                    @elseif($selectedRecom === 'calculate')
                        <td class="text-center">{{ number_format($item['averageTotal'], 3) }}</td>
                    @endif
                    <td class="text-start">{{ $item['rank'] }}</td>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
