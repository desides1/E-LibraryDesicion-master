<table class="table" id="table3">
    <thead class="table-light">
        <tr>
            <th>Alternatif</th>
            @foreach ($criteria as $item)
                <th class="text-center">{{ $item->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($normalization as $item)
            <tr>
                <td>{{ $item['title'] }}</td>
                <td class="text-center">{{ $item['normalization-year-publication-value'] }}</td>
                <td class="text-center">{{ $item['normalization-request-book-value'] }}</td>
                <td class="text-center">{{ $item['normalization-book-price-value'] }}</td>
                <td class="text-center">{{ $item['normalization-library-stock-value'] }}</td>
                <td class="text-center">{{ $item['normalization-publisher-stock-value'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
