@push('scripts')
    <script defer>
        $(document).ready(() => {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#table1').on('click', '.status-container', function() {
                const $this = $(this);
                const userId = $this.closest('.status-container').data('user-id');

                Swal.fire({
                    icon: 'question',
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin mengubah status?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Saya yakin',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0F345E',
                    cancelButtonColor: '#BB1F26',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: `{{ url('/weight-criteria') }}/${userId}/toggle-status`,
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil',
                                    text: 'Status berhasil diubah',
                                    icon: 'success',
                                    confirmButtonColor: '#0F345E',
                                }).then((result) => {
                                    window.location.href =
                                        "{{ url('/weight-criteria') }}";
                                });
                            },
                            error: function(error) {
                                // console.log(error);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
