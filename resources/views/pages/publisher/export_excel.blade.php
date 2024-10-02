<table>
    <thead>
        <tr>
            <th style="text-align: left; font-weight: bold;">No</th>
            <th style="text-align: left; font-weight: bold;">Judul Buku</th>
            <th style="text-align: left; font-weight: bold;">Penulis</th>
            <th style="text-align: left; font-weight: bold;">Kategori Buku</th>
            <th style="text-align: left; font-weight: bold;">ISBN</th>
            <th style="text-align: left; font-weight: bold;">Tahun Terbit</th>
            <th style="text-align: left; font-weight: bold;">Jenis Buku</th>
            <th style="text-align: left; font-weight: bold;">Harga Buku</th>
            <th style="text-align: left; font-weight: bold;">Stok Buku</th>
            <th style="text-align: left; font-weight: bold;">Abstract Buku</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td style="text-align: left;">{{ $loop->iteration }}</td>
                <td style="text-align: left;">{{ $item->title }}</td>
                <td style="text-align: left;">{{ $item->author }}</td>
                <td style="text-align: left;">{{ $item->category->name }}</td>
                <td style="text-align: left;">{{ $item->isbn }}</td>
                <td style="text-align: left;">{{ $item->publication_date }}</td>
                <td style="text-align: left;">{{ $item->type_book }}</td>
                <td style="text-align: left;">{{ $item->price }}</td>
                <td style="text-align: left;">{{ $item->available_stock }}</td>
                <td style="text-align: left;">{{ $item->abstract }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
