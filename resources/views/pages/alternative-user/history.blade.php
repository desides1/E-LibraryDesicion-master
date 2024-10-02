<table class="table" id="table1">
    <thead class="table-light">
        <tr>
            <th class="text-center">No</th>
            <th>Nama Buku</th>
            <th>ISBN</th>
            <th>Penulis</th>
            <th class="text-center">Tahun Terbit</th>
            <th>Koleksi Buku</th>
            <th class="text-center">Tahun Pengajuan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->book->title }}</td>
                <td>{{ $item->book->isbn }}</td>
                <td>{{ $item->book->author }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->book->publication_date)->format('Y') }}</td>
                <td>{{ $item->book->user_id == 37 ? 'Perpustakaan' : 'Penerbit' }}</td>
                <td class="text-center">{{ $item->year }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
