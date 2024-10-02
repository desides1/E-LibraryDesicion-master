@push('scripts')
    <script defer>
        const preventInput = (element) => {
            element.addEventListener('keydown', (event) => {
                event.preventDefault();
            });
        }

        const validateInput = (inputElement) => {
            const inputValue = inputElement.value;

            if (/\s/.test(inputValue)) {
                inputElement.value = inputValue.replace(/\s/g, '');
            }
        };

        const togglePassword = (inputId) => {
            const passwordInput = document.getElementById(inputId);
            const togglePasswordBtn = document.querySelector(`[toggle="#${inputId}"]`);

            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            togglePasswordBtn.classList.toggle('fa-eye');
            togglePasswordBtn.classList.toggle('fa-eye-slash');
        }

        $('#formChangePassword').submit(function(e) {
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
                        url: $('#formChangePassword').attr('action'),
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Ubah kata sandi berhasil',
                                icon: 'success',
                                confirmButtonColor: '#0F345E',
                            }).then((result) => {
                                window.location.href = '/profile-management';
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
                            } else if (xhr.status === 424) {
                                var errors = xhr.responseJSON.error_old;
                                var errorMessage = errors;

                                Swal.fire({
                                    title: 'Kesalahan Validasi',
                                    text: 'Password lama anda salah',
                                    icon: 'error',
                                    confirmButtonColor: '#0F345E',
                                });
                            } else if (xhr.status === 500) {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: 'Ubah Kata Sandi Gagal',
                                    icon: 'error',
                                    confirmButtonColor: '#0F345E',
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
