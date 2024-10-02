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

        const restrictNumber = (inputElement) => {
            inputElement.addEventListener("input", () => {
                let inputText = inputElement.value;

                inputText = inputText.replace(/[^0-9]/g, '').replace(/^[^1-9]0/, '0');

                inputText = inputText.replace(/\.+/g, '.');

                if (inputText.length > 0 && inputText[0] === ' ') {
                    inputText = inputText.trim();
                }

                inputText = inputText.replace(/^0+/, '');

                if (inputText.length > 3) {
                    inputText = inputText.slice(0, 3);
                }


                inputElement.value = inputText;
            });
        };

        const radioButtons = document.querySelectorAll('input[name="sub_criterias"]');

        radioButtons.forEach(radioButton => {
            radioButton.addEventListener('change', () => {
                const subCriteriaContent = document.getElementById('subCriteriaContent');
                subCriteriaContent.style.display = radioButton.value === 'Iya' ? '' : 'none';
            });
        });

        const deleteSubCriteria = (button) => {
            const subCriteria = button.closest('.subcriteria');

            if (subCriteria.previousElementSibling !== null) {
                subCriteria.remove();

                const subCriteriaList = document.querySelectorAll('.subcriteria');
                subCriteriaList.forEach((subCriteria, index) => {
                    const label = subCriteria.querySelector('label[for="subcriteria"]');
                    label.textContent = `Sub Kriteria ${index + 1}`;
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak bisa menghapus',
                    text: 'Sub kriteria pertama tidak bisa dihapus.'
                });
            }
        };

        document.addEventListener("DOMContentLoaded", function() {
            const radioYes = document.getElementById("flexRadioDefault3");
            const nameSubInput = document.getElementById("name_sub");
            const valueInput = document.getElementById("value");
            const radioNo = document.getElementById("flexRadioDefault4");
            radioYes.addEventListener("change", function() {
                nameSubInput.setAttribute("required", this.checked ? "" : null);
                valueInput.setAttribute("required", this.checked ? "" : null);
            });

            radioNo.addEventListener("change", function() {
                nameSubInput.removeAttribute("required");
                valueInput.removeAttribute("required");
            });
        });

        document.getElementById('addSubCriteria').addEventListener('click', () => {
            const template = document.querySelector('.subcriteria').cloneNode(true);

            const hiddenInput = template.querySelector('input[name="id_sub[]"]');
            if (hiddenInput) {
                hiddenInput.remove();
            }

            template.querySelector('#name_sub').value = "";
            template.querySelector('#value').value = "";

            const subCriteriaContainer = document.getElementById('subCriteriaContainer');
            const subCriteriaCount = subCriteriaContainer.getElementsByClassName('subcriteria').length + 1;
            template.querySelector('label[for="subcriteria"]').textContent = `Sub Kriteria ${subCriteriaCount}`;

            subCriteriaContainer.appendChild(template);
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
                            url: "{{ url('/weight-criteria') }}",
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
                                        "{{ url('/weight-criteria') }}";
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
                                    Swal.fire({
                                        title: 'Kesalahan Validasi',
                                        text: 'Bobot Kriteria melebihi 100%',
                                        icon: 'error',
                                        confirmButtonColor: '#0F345E',
                                    });
                                } else if (xhr.status === 425) {
                                    Swal.fire({
                                        title: 'Kesalahan Validasi',
                                        text: 'Nilai Sub Kriteria melebihi 100%',
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
                                        "{{ url('/weight-criteria') }}";
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
                                } else if (xhr.status === 424) {
                                    Swal.fire({
                                        title: 'Kesalahan Validasi',
                                        text: 'Bobot Kriteria melebihi 100%',
                                        icon: 'error',
                                        confirmButtonColor: '#0F345E',
                                    });
                                } else if (xhr.status === 425) {
                                    Swal.fire({
                                        title: 'Kesalahan Validasi',
                                        text: 'Nilai Sub Kriteria melebihi 100%',
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
