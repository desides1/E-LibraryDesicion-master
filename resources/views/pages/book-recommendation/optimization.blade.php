<table class="table" id="table4">
    <thead class="table-light">
        <tr>
            <th>Alternatif</th>
            @foreach ($criteria as $item)
                <th class="text-center">{{ $item->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($optimization as $item)
            <tr>
                <td>{{ $item['title'] }}</td>
                <td class="text-center">{{ $item['optimization-year'] }}</td>
                <td class="text-center">{{ $item['optimization-request'] }}</td>
                <td class="text-center">{{ $item['optimization-price'] }}</td>
                <td class="text-center">{{ $item['optimization-library'] }}</td>
                <td class="text-center">{{ $item['optimization-publisher'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
