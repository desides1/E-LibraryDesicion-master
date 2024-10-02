<table>
    <thead>
        <tr>
            <th style="text-align: left; font-weight: bold;">No</th>
            <th style="text-align: left; font-weight: bold;">Judul Buku</th>
            <th style="text-align: left; font-weight: bold;">Status</th>
            <th style="text-align: left; font-weight: bold;">Nama Pemustaka</th>
            <th style="text-align: left; font-weight: bold;">Program Studi/Unit</th>
            <th style="text-align: left; font-weight: bold;">ISBN Buku</th>
            <th style="text-align: left; font-weight: bold;">Penerbit</th>
            <th style="text-align: left; font-weight: bold;">Tahun Terbit</th>
            <th style="text-align: left; font-weight: bold;">Tanggal Pengajuan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td style="text-align: left;">{{ $loop->iteration }}</td>
                <td style="text-align: left;">{{ $item->publisher->title }}</td>
                <td style="text-align: left;">{{ $item->borrowed->status }}</td>
                <td style="text-align: left;">{{ $item->borrowed->name }}</td>
                <td style="text-align: left;">{{ $item->borrowed->major }}</td>
                <td style="text-align: left;">{{ $item->publisher->isbn }}</td>
                <td style="text-align: left;">
                    {{ $item->publisher->user->name ? $item->publisher->user->name : $item->publisher->name }}</td>
                <td style="text-align: left;">{{ $item->publisher->publication_date }}</td>
                <td style="text-align: left;">{{ $item->year }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
