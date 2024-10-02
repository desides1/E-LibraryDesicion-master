@push('scripts')
    <script defer>
        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                width: '100%',
            });

            $('#mainSelect').on('change', function() {
                if ($(this).val() === 'calculate') {
                    $('#calculateDropdown').show();
                } else {
                    $('#calculateDropdown').hide();
                    removeCalavgFromUrl();
                    $('#filterForm').submit();
                }
            });

            if ($('#mainSelect').val() === 'calculate') {
                $('#calculateDropdown').show();
            }

            $('#selectMajor').on('change', function() {
                $('#filterForm').submit();
            });

            function removeCalavgFromUrl() {
                const url = new URL(window.location.href);
                url.searchParams.delete('calavg');
                window.history.replaceState(null, '', url);
            }

            $('#btn-preview').on('click', function(event) {
                if ($('#mainSelect').val() === 'calculate') {
                    const checkboxes = $('input[name="calavg[]"]:checked');
                    if (checkboxes.length === 0) {
                        event.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Anda harus memilih setidaknya satu metode kalkulasi!',
                            confirmButtonColor: '#0F345E',
                        });
                    }
                }
            });

            $('#printLink').on('click', function(event) {
                event.preventDefault();

                const selectedCalavg = $('input[name="calavg[]"]:checked')
                    .map(function() {
                        return $(this).val();
                    })
                    .get();

                const url = new URL($(this).attr('href'));

                if (selectedCalavg.length > 0) {
                    selectedCalavg.forEach(function(value) {
                        url.searchParams.append('calavg[]', value);
                    });
                }

                window.open(url.toString(), '_blank');
            });
        });
    </script>
@endpush
