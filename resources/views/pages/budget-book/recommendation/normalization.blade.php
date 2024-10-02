<table class="table" id="table3">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>Alternatif</th>
            @foreach ($criteria as $item)
                <th class="text-center">{{ $item->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($normalization as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ Str::limit($item['title'], 75) }}</td>
                <td class="text-center">{{ number_format($item['normalization-year-publication-value'], 3) }}</td>
                <td class="text-center">{{ number_format($item['normalization-request-book-value'], 3) }}</td>
                <td class="text-center">{{ number_format($item['normalization-book-price-value'], 3) }}</td>
                <td class="text-center">{{ number_format($item['normalization-library-stock-value'], 3) }}</td>
                <td class="text-center">{{ number_format($item['normalization-publisher-stock-value'], 3) }}</td>                
            </tr>
        @endforeach
    </tbody>
</table>
