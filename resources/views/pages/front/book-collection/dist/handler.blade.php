@push('scripts')
    <script defer>
        window.addEventListener('load', function() {
            var cards = document.querySelectorAll('.blog-item');

            var maxHeight = 0;
            cards.forEach(function(card) {
                var cardHeight = card.offsetHeight;
                if (cardHeight > maxHeight) {
                    maxHeight = cardHeight;
                }
            });

            cards.forEach(function(card) {
                card.style.height = maxHeight + 'px';
            });
        });
    </script>
@endpush
