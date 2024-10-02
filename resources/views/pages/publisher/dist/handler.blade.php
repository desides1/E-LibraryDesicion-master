@push('scripts')
    <script defer>
        const autoSize = (element) => {
            element.style.height = "auto";
            element.style.height = (element.scrollHeight) + "px";
        };

        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                width: '100%',
            });

            $('.js-example-basic-single').on('change', function() {
                $('#filterForm').submit();
            });
        });
    </script>
@endpush
