<table class="table" id="table5">
    <thead class="table-light">
        <tr>
            <th>Alternatif</th>
            <th class="text-center">Max</th>
            <th class="text-center">Min</th>
            <th class="text-center">Yi</th>
            <th class="text-center">Peringkat</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rank as $item)
            <tr>
                <td>{{ $item['title'] }}</td>
                <td class="text-center">{{ $item['maxValue'] }}</td>
                <td class="text-center">{{ $item['minValue'] }}</td>
                <td class="text-center">{{ $item['yiValue'] }}</td>
                <td class="text-center">{{ $item['rank'] }}</td>
                <td class="text-center">
                    @if ($item['status'] == 'Rekomendasi Sesuai Anggaran')
                        <button class="btn btn-outline-success w-full">{{ $item['status'] }} </button>
                    @else
                        <button class="btn btn-outline-danger w-full">{{ $item['status'] }}</button>
                    @endif
                </td>
                {{-- <td>Rp{{ number_format($item['budget_total'], 0, ',', '.') }}</td> --}}
            </tr>
        @endforeach
    </tbody>
</table>
