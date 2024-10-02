<script defer>
    const autoSize = (element) => {
        element.style.height = "auto";
        element.style.height = (element.scrollHeight) + "px";
    };

    document.getElementById("cancelButton").addEventListener("click", function() {
        window.location.href = "{{ url('/book-budget') }}";
    });

    const restrictInput = (inputElement) => {
        inputElement.addEventListener("input", () => {
            let inputText = inputElement.value;
            inputText = inputText.replace(/[^a-zA-Z0-9 .,!\-]/g, '');
            inputText = inputText.replace(/ +/g, ' ');

            if (inputText.length > 0 && inputText[0] === ' ') {
                inputText = inputText.trim();
            }

            inputElement.value = inputText;
        });
    };

    const restrictNumber = (inputElement) => {
        inputElement.addEventListener("input", () => {
            let inputText = inputElement.value;

            inputText = inputText.replace(/[^0-9]/g, '').replace(/^[^1-9]0/, '0');

            inputText = inputText.replace(/\.+/g, '.');

            if (inputText.length > 0 && inputText[0] === ' ') {
                inputText = inputText.trim();
            }

            inputText = inputText.replace(/^0+/, '');

            inputElement.value = inputText;
        });
    };

    const restrictNumberPn = (inputElement) => {
        inputElement.addEventListener("input", () => {
            let inputText = inputElement.value;

            inputText = inputText.replace(/[^0-9]/g, '').replace(/^[^1-9]0/, '0');

            inputText = inputText.replace(/\.+/g, '.');

            if (inputText.length > 0 && inputText[0] === ' ') {
                inputText = inputText.trim();
            }

            inputText = inputText.replace(/^0+/, '');

            const inputValue = parseInt(inputText);

            var selectedStock = 100;

            if (inputValue > selectedStock) {
                inputText = selectedStock.toString();
            }

            inputElement.value = inputText;
        });
    };

    const restrictNumbers = (inputElement) => {
        inputElement.addEventListener("input", () => {
            let inputText = inputElement.value;

            inputText = inputText.replace(/[^0-9 -]/g, '').replace(/^[^1-9]0 /, '0');

            inputText = inputText.replace(/\.+/g, '.');

            if (inputText.length > 0 && inputText[0] === ' ') {
                inputText = inputText.trim();
            }

            inputText = inputText.replace(/^0+/, '');

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
                        url: "{{ url('/book-budget') }}",
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
                                    "{{ url('/book-budget') }}";
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
                                    "{{ url('/book-budget') }}";
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
