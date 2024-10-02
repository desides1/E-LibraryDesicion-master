<div class="d-flex justify-content-center">
    @if ($data->hasPages())
        <ul class="pagination">
            <!-- Previous Page Link -->
            @if ($data->onFirstPage())
                <li class="disabled page-item"><span class="page-link">&laquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $data->previousPageUrl() }}" rel="prev">&laquo;</a>
                </li>
            @endif

            <!-- Pagination Elements -->
            @foreach ($data->links()->elements as $element)
                <!-- "Three Dots" Separator -->
                @if (is_string($element))
                    <li class="disabled page-item"><span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                <!-- Array Of Links -->
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $data->currentPage())
                            <li class="active page-item"><span class="page-link">{{ $page }}</span></li>
                        @elseif (
                            $page == 1 ||
                                $page == $data->lastPage() ||
                                ($page >= $data->currentPage() - 2 && $page <= $data->currentPage() + 2))
                            <li class="page-item"><a class="page-link"
                                    href="{{ $url }}">{{ $page }}</a></li>
                        @elseif ($page == $data->currentPage() - 3 || $page == $data->currentPage() + 3)
                            <li class="disabled page-item"><span class="page-link">...</span></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            <!-- Next Page Link -->
            @if ($data->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $data->nextPageUrl() }}" rel="next">&raquo;</a>
                </li>
            @else
                <li class="disabled page-item"><span class="page-link">&raquo;</span></li>
            @endif
        </ul>
    @endif
</div>
