@push('styles')
    <style>
        .zoom-effect {
            width: 100%;
            height: 220px;
            object-fit: contain;
            transition: transform 0.5s ease;
        }

        .zoom-effect:hover {
            transform: scale(1.2);
        }

        .zoom-effect:active {
            transform: scale(1.3);
        }
    </style>
@endpush
