@foreach ($userAlternative as $item)
    <ul>
        <li>{{ $item->borrowed->name }} ({{ $item->borrowed->major }})</li>
    </ul>
@endforeach

<div data-wow-delay="0.1s" class="mt-3" id="pagination-container">
    {{ $userAlternative->links() }}
</div>
