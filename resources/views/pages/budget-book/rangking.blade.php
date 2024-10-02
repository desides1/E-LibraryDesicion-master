<table class="table" id="table12">
    <thead class="table-light">
        <tr>
            <th class="text-start">Peringkat</th>
            <th>Alternatif</th>
            @if ($selectedRecom === 'moora')
                <th class="text-center">Max</th>
                <th class="text-center">Min</th>
                <th class="text-center">Yi</th>
            @elseif ($selectedRecom === 'saw' || $selectedRecom === 'wpm')
                <th class="text-center">Vi</th>
            @elseif ($selectedRecom === 'topsis')
                <th class="text-center">D+</th>
                <th class="text-center">D-</th>
                <th class="text-center">Vi</th>
            @elseif ($selectedRecom === 'calculate')
                @if (in_array('calmoora', $calulateSelected))
                    <th class="text-center">Nilai MOORA</th>
                @endif
                @if (in_array('caltopsis', $calulateSelected))
                    <th class="text-center">Nilai TOPSIS</th>
                @endif
                @if (in_array('calsaw', $calulateSelected))
                    <th class="text-center">Nilai SAW</th>
                @endif
                @if (in_array('calwpm', $calulateSelected))
                    <th class="text-center">Nilai WPM</th>
                @endif
                <th class="text-center">Nilai Rata-Rata</th>
            @endif
            @if ($selectedMajor !== 'all')
                <th class="text-center">Aksi</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($rank as $item)
            <tr>
                <td>Peringkat {{ $loop->iteration }}</td>
                <td>{{ Str::limit($item['title'], 75) }}</td>
                @if ($selectedRecom === 'moora')
                    <td class="text-center">{{ number_format($item['maxValue'], 3) }}</td>
                    <td class="text-center">{{ number_format($item['minValue'], 3) }}</td>
                    <td class="text-center">{{ number_format($item['yiValue'], 3) }}</td>
                @elseif($selectedRecom === 'topsis')
                    <td class="text-center">{{ number_format($item['d-plus-alternative'], 3) }}</td>
                    <td class="text-center">{{ number_format($item['d-minus-alternative'], 3) }}</td>
                    <td class="text-center">{{ number_format($item['viValueTopsis'], 3) }}</td>
                @elseif($selectedRecom === 'saw' || $selectedRecom === 'wpm')
                    <td class="text-center">{{ number_format($item['viValue'], 3) }}</td>
                @elseif($selectedRecom === 'calculate')
                    @if (in_array('calmoora', $calulateSelected))
                        <td class="text-center">{{ number_format($item['yiValue-moora'], 3) }}</td>
                    @endif
                    @if (in_array('caltopsis', $calulateSelected))
                        <td class="text-center">{{ number_format($item['viValue-topsis'], 3) }}</td>
                    @endif
                    @if (in_array('calsaw', $calulateSelected))
                        <td class="text-center">{{ number_format($item['viValue-saw'], 3) }}</td>
                    @endif
                    @if (in_array('calwpm', $calulateSelected))
                        <td class="text-center">{{ number_format($item['viValue-wpm'], 3) }}</td>
                    @endif
                    <td class="text-center">{{ number_format($item['averageTotal'], 3) }}</td>
                @endif
                @if ($selectedMajor !== 'all')
                    <td class="text-center">
                        @if ($item['publisher-stock'] > 1)
                            <button class="btn btn-tosca text-light icon icon-left fw-bolder user-manage-btn me-1"
                                id="manageBook" data-price="{{ $item['book-price'] }}"
                                data-title="{{ $item['title'] }}" data-id="{{ $item['publisher_id'] }}"
                                data-stocks = "{{ $item['publisher-stock'] }}" data-title="{{ $item['title'] }}"><i
                                    class="fas fa-plus me-2"></i>Tambah</button>
                            <button class="btn btn-navy text-light icon icon-left fw-bolder user-detail-btn"
                                id="detailBook" data-id="{{ $item['publisher_id'] }}"><i
                                    class="fas fa-info"></i></button>
                        @else
                            <button class="btn btn-danger">Stok Habis</button>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>

@include('pages.budget-book.detail_rank')
@include('pages.budget-book.dist.rank_dist')
@include('pages.budget-book.dist.payment')
