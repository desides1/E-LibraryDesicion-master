@push('scripts')
    <script defer>
        const restrictInput = (inputElement) => {
            inputElement.addEventListener("input", () => {
                let inputText = inputElement.value;
                inputText = inputText.replace(/[^a-zA-Z ]/g, '');
                inputText = inputText.replace(/ +/g, ' ');

                if (inputText.length > 0 && inputText[0] === ' ') {
                    inputText = inputText.trim();
                }

                inputElement.value = inputText;
            });
        };

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
                            url: "{{ url('/unit') }}",
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
                                    window.location.href =
                                        "{{ url('/unit') }}";
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
                            url: `{{ url('/unit') }}/${userId}/toggle-status`,
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil',
                                    text: 'Status berhasil diubah',
                                    icon: 'success',
                                    confirmButtonColor: '#0F345E',
                                }).then((result) => {
                                    window.location.href =
                                        "{{ url('/unit') }}";
                                });
                            },
                            error: function(error) {
                                // console.log(error);
                            }
                        });
                    }
                });
            });

            $('#formUpdate').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                Swal.fire({
                    icon: 'question',
                    title: 'Apakah anda yakin?',
                    text: 'Pastikan semua data yang dimasukkan sudah benar!',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Saya yakin',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0F345E',
                    cancelButtonColor: '#BB1F26',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: $('#formUpdate').attr('action'),
                            type: 'POST',
                            data: formData,
                            dataType: 'json',
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil',
                                    text: 'Ubah data berhasil',
                                    icon: 'success',
                                    confirmButtonColor: '#0F345E',
                                }).then((result) => {
                                    window.location.href =
                                        "{{ url('/unit') }}";
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
                                        title: 'Input Tidak Valid',
                                        text: errorMessage,
                                        icon: 'error',
                                        confirmButtonColor: '#0F345E',
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal',
                                        text: 'Ubah data gagal',
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
@endpush
