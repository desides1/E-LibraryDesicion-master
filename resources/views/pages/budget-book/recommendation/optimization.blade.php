<table class="table" id="table4">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>Alternatif</th>
            @if ($selectedRecom != 'wpm')
                @foreach ($criteria as $item)
                    <th class="text-center">{{ $item->name }}</th>
                @endforeach
            @elseif ($selectedRecom === 'wpm')
                <th class="text-center">Si</th>
                <th class="text-center">SiN</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($optimization as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ Str::limit($item['title'], 75) }}</td>
                @if ($selectedRecom != 'wpm')
                    <td class="text-center">{{ number_format($item['optimization-year'], 3) }}</td>
                    <td class="text-center">{{ number_format($item['optimization-request'], 3) }}</td>
                    <td class="text-center">{{ number_format($item['optimization-price'], 3) }}</td>
                    <td class="text-center">{{ number_format($item['optimization-library'], 3) }}</td>
                    <td class="text-center">{{ number_format($item['optimization-publisher'], 3) }}</td>
                @elseif ($selectedRecom === 'wpm')
                    <td class="text-center">{{ number_format($item['optimization-value'], 3) }}</td>
                    <td class="text-center">{{ number_format($item['optimization-result'], 3) }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
