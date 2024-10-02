<script defer>
    $(document).ready(() => {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.deleteButton').click(function(e) {
            e.preventDefault();

            var itemId = $(this).closest('form').find('input[name="id"]').val();
            var form = $(this).closest('form');

            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi Hapus?',
                text: "Apakah anda yakin ingin menghapus item ini!",
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0F345E',
                cancelButtonColor: '#BB1F26',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            _method: 'DELETE',
                            item_id: itemId
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Hapus data berhasil',
                                icon: 'success',
                                confirmButtonColor: '#0F345E',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status === 422) {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: 'Buku dengan status Terealisasi tidak dapat dihapus',
                                    icon: 'error',
                                    confirmButtonColor: '#0F345E',
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: 'Hapus data gagal',
                                    icon: 'error',
                                    confirmButtonColor: '#0F345E',
                                });
                            }
                        }

                    });
                }
            });
        });

        $('#table1').on('click', '#process', function() {
            const $this = $(this);
            const userId = $this.closest('#process').data('user-id');
            // console.log(userId);

            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin mengubah status pengadaan buku',
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
                        url: `{{ url('/book-budget/history') }}/${userId}/update-status-process`,
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Status berhasil diubah',
                                icon: 'success',
                                confirmButtonColor: '#0F345E',
                            }).then((result) => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: 'Maaf, stok buku yang tersedia di penerbit tidak mencukupi untuk jumlah buku yang diminta',
                                    icon: 'error',
                                    confirmButtonColor: '#0F345E',
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: 'Status gagal diubah',
                                    icon: 'error',
                                    confirmButtonColor: '#0F345E',
                                });
                            }
                        }
                    });
                }
            });
        });

        $('#table1').on('click', '#realization', function() {
            const $this = $(this);
            const userId = $this.closest('#realization').data('user-id');
            // console.log(userId);

            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin mengubah status pengadaan buku',
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
                        url: `{{ url('/book-budget/history') }}/${userId}/update-status-realization`,
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Status berhasil diubah',
                                icon: 'success',
                                confirmButtonColor: '#0F345E',
                            }).then((result) => {
                                window.location.reload();
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
