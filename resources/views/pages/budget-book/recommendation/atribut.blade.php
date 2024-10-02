<table class="table" id="table1">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>Alternatif</th>
            @foreach ($criteria as $item)
                <th>{{ $item->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($alternative as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item['title'] }}</td>
                <td>{{ $item['year-publication'] }}</td>
                <td>{{ $item['request-book'] }}</td>
                <td>Rp{{ number_format($item['book-price'], 0, ',', '.') }}</td>
                <td>{{ $item['library-stock'] }}</td>
                <td>{{ $item['publisher-stock'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
