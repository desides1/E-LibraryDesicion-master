<script defer>
    document.getElementById("cancelButton").addEventListener("click", function() {
        window.location.href = "/user-book";
    });

    $(document).ready(() => {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#formInsert').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            Swal.fire({
                icon: 'question',
                title: 'Apakah anda yakin?',
                text: 'Pastikan semua data yang dimasukkan sudah benar',
                showCancelButton: true,
                confirmButtonText: 'Ya, Saya yakin',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0F345E',
                cancelButtonColor: '#BB1F26',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/user-book',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Tambah data berhasil',
                                icon: 'success',
                                confirmButtonColor: '#0F345E',
                            }).then((result) => {
                                window.location.href = '/user-book';
                            });
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                var errorMessage = '';

                                for (var key in errors) {
                                    errorMessage += errors[key][0] + '\n';
                                }

                                Swal.fire({
                                    title: 'Kesalahan Validasi',
                                    text: errorMessage,
                                    icon: 'error',
                                    confirmButtonColor: '#0F345E',
                                });
                            } else if (xhr.status === 423) {
                                var errors = xhr.responseJSON.errors;
                                var errorMessage = '';

                                for (var key in errors) {
                                    errorMessage += errors[key][0] + '\n';
                                }

                                Swal.fire({
                                    title: 'Kesalahan Validasi',
                                    text: 'Harap pilih salah satu pengadaan buku antara perpustakaan atau penerbit.',
                                    icon: 'error',
                                    confirmButtonColor: '#0F345E',
                                });

                            } else {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: 'Tambah Data Gagal',
                                    icon: 'error',
                                    confirmButtonColor: '#0F345E',
                                });
                            }
                        }
                    });
                }
            });
        });

    });
</script>
