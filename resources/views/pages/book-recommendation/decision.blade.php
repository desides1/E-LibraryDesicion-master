<table class="table" id="table2">
    <thead class="table-light">
        <tr>
            <th>Alternatif</th>
            @foreach ($criteria as $item)
                <th>{{ $item->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($decision as $item)
            <tr>
                <td>{{ $item['title'] }}</td>
                <td>{{ $item['year-publication-value'] }}</td>
                <td>{{ $item['request-book-value'] }}</td>
                <td>{{ $item['book-price-value'] }}</td>
                <td>{{ $item['library-stock-value'] }}</td>
                <td>{{ $item['publisher-stock-value'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
