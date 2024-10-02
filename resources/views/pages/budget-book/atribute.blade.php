<table class="table" id="table12">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>Alternatif</th>
            @foreach ($criteria as $item)
                @if ($item->code != 'C4' && $item->code != 'C5')
                    <th>{{ $item->name }}</th>
                @endif
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
            </tr>
        @endforeach
        <b class="text-danger">Maaf, rekomendasi buku tidak dapat dilakukan karena setidaknya diperlukan lebih dari satu data alternatif!</b>
    </tbody>
</table>
