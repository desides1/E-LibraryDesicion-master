@push('scripts')
    <script defer>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();

            $('.js-example-basic-single').on('change', function() {
                $('#filterForm').submit();
            });
        });
    </script>
@endpush
