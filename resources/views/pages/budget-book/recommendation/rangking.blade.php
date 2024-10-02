<table class="table" id="table5">
    <thead class="table-light">
        <tr>
            <th class="text-start">Peringkat</th>
            <th>Alternatif</th>
            @if ($selectedRecom === 'moora')
                <th class="text-center">Max</th>
                <th class="text-center">Min</th>
                <th class="text-center">Yi</th>
            @elseif ($selectedRecom === 'saw' || $selectedRecom === 'wpm')
                <th class="text-center">Vi</th>
            @elseif ($selectedRecom === 'topsis')
                <th class="text-center">D+</th>
                <th class="text-center">D-</th>
                <th class="text-center">Vi</th>
            @elseif ($selectedRecom === 'calculate')
                <th class="text-center">Avg</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($rank as $item)
            <tr>
                <td>Peringkat {{ $loop->iteration }}</td>
                <td>{{ Str::limit($item['title'], 75) }}</td>
                @if ($selectedRecom === 'moora')
                    <td class="text-center">{{ number_format($item['maxValue'], 3) }}</td>
                    <td class="text-center">{{ number_format($item['minValue'], 3) }}</td>
                    <td class="text-center">{{ number_format($item['yiValue'], 3) }}</td>
                @elseif($selectedRecom === 'topsis')
                    <td class="text-center">{{ number_format($item['d-plus-alternative'], 3) }}</td>
                    <td class="text-center">{{ number_format($item['d-minus-alternative'], 3) }}</td>
                    <td class="text-center">{{ number_format($item['viValueTopsis'], 3) }}</td>
                @elseif($selectedRecom === 'saw' || $selectedRecom === 'wpm')
                    <td class="text-center">{{ number_format($item['viValue'], 3) }}</td>
                @elseif($selectedRecom === 'calculate')
                    <td class="text-center">{{ number_format($item['averageTotal'], 3) }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
