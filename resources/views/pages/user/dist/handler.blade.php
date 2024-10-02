<script defer>
    const validateInput = (inputElement) => {
        const inputValue = inputElement.value;

        if (/\s/.test(inputValue)) {
            inputElement.value = inputValue.replace(/\s/g, '');
        }
    };

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

    const restrictNumber = (inputElement) => {
        inputElement.addEventListener("input", () => {
            let inputText = inputElement.value;

            inputText = inputText.replace(/[^0-9.]/g, '').replace(/^[^1-9]0/, '0');

            inputText = inputText.replace(/\.+/g, '.');

            if (inputText.length > 0 && inputText[0] === ' ') {
                inputText = inputText.trim();
            }

            inputText = inputText.replace(/^0+/, '');

            inputElement.value = inputText;
        });
    };

    setTimeout(() => $('#success-alert').alert('close'), 7000);

    const togglePassword = (inputId) => {
        const passwordInput = document.getElementById(inputId);
        const togglePasswordBtn = document.querySelector(`[toggle="#${inputId}"]`);

        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        togglePasswordBtn.classList.toggle('fa-eye');
        togglePasswordBtn.classList.toggle('fa-eye-slash');
    }
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
                        url: `{{ url('/user-management') }}/${userId}/toggle-status`,
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Status berhasil diubah',
                                icon: 'success',
                                confirmButtonColor: '#0F345E',
                            }).then((result) => {
                                window.location.href =
                                    "{{ url('/user-management') }}";
                            });
                        },
                        error: function(error) {
                            // console.log(error);
                        }
                    });
                }
            });
        });

        $('#table1').on('click', '.role-container', function() {
            const $this = $(this);
            const userId = $this.closest('.role-container').data('user-id');

            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin mengubah hak akses?',
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
                        url: `{{ url('/user-management') }}/${userId}/toggle-role`,
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Peran berhasil diubah',
                                icon: 'success',
                                confirmButtonColor: '#0F345E',
                            }).then((result) => {
                                window.location.href =
                                    "{{ url('/user-management') }}";
                            });
                        },
                        error: function(error) {
                            // console.log(error);
                        }
                    });
                }
            });
        });

        $('#table1').on('click', '.user-edit-btn', function() {
            const userManageModal = new bootstrap.Modal(document.getElementById('userManageModal'));
            const modalTitle = document.getElementById('userManageModalTitle');
            const nameInput = document.getElementById('name');
            const idInput = document.getElementById('id');
            const userId = $(this).data('user-id');

            $.ajax({
                url: `{{ url('/user-management') }}/${userId}/edit`,
                method: 'GET',
                dataType: 'json',
                success: function(userData) {
                    modalTitle.innerText = 'Ubah Kata Sandi Pengguna';
                    nameInput.value = userData.data.name;
                    idInput.value = userData.data.id;
                    userManageModal.show();
                },
                error: function(error) {
                    console.error('Error fetching user data:', error);
                }
            });
        });


        $('#formChangePassword').submit(function(e) {
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
                    const post_id = $('#id').val();

                    $.ajax({
                        url: `{{ url('/user-management') }}/${post_id}/reset-password`,
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Password berhasil diubah',
                                icon: 'success',
                                confirmButtonColor: '#0F345E',
                            }).then((result) => {
                                window.location.href =
                                    "{{ url('/user-management') }}";
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
                                    text: 'Password gagal diubah',
                                    icon: 'error',
                                    confirmButtonColor: '#0F345E',
                                });
                            }
                        }
                    });
                }
            });
        });

        $('#formInsert').submit(function(e) {
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
                        url: "{{ url('/user-management') }}",
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Tambah pengguna berhasil',
                                icon: 'success',
                                confirmButtonColor: '#0F345E',
                            }).then((result) => {
                                window.location.href =
                                    "{{ url('/user-management') }}";
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
                                    text: 'Tambah data gagal',
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
