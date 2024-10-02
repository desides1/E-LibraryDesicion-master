<script defer>
    const excel_file = document.getElementById('importExcel');

    excel_file.addEventListener('change', (event) => {

        if (!['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']
            .includes(event.target.files[0].type)) {
            document.getElementById('excelData').innerHTML =
                '<div class="alert alert-danger">Hanya format file .xlsx yang diperbolehkan</div>';

            // excel_file.value = '';
            return false;
        }

        var reader = new FileReader();
        reader.readAsArrayBuffer(event.target.files[0]);
        reader.onload = function(event) {

            var data = new Uint8Array(reader.result);

            var work_book = XLSX.read(data, {
                type: 'array'
            });

            var sheet_name = work_book.SheetNames;
            var sheet_data = XLSX.utils.sheet_to_json(work_book.Sheets[sheet_name[0]], {
                header: 1
            });

            if (sheet_data.length > 0) {
                var thead_output = '<thead class="table-light"><tr>';
                for (var cell = 0; cell < sheet_data[0].length; cell++) {
                    thead_output += '<th>' + sheet_data[0][cell] + '</th>';
                }
                thead_output += '</tr></thead>';

                var tbody_output = '<tbody>';
                for (var row = 1; row < sheet_data.length; row++) {
                    tbody_output += '<tr>';
                    for (var cell = 0; cell < sheet_data[row].length; cell++) {
                        tbody_output += '<td>' + sheet_data[row][cell] + '</td>';
                    }
                    tbody_output += '</tr>';
                }
                tbody_output += '</tbody>';

                var table_output = '<div class="fw-bolder">Tabel Preview File Excel Buku</div>' +
                    '<table class="table" id="table1">' + thead_output + tbody_output + '</table>';

                document.getElementById('excelData').innerHTML = table_output;

                let table1 = document.querySelector("#table1");
                let dataTable = new simpleDatatables.DataTable(table1);
            }
            // excel_file.value = '';
        }
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
                        url: "{{ url('/book-list/import-excel') }}",
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Import data berhasil',
                                icon: 'success',
                                confirmButtonColor: '#0F345E',
                            }).then((result) => {
                                window.location.href =
                                    "{{ url('/book-list') }}";
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
                                    text: 'Terdapat kesalahan pada data yang anda masukkan. Mohon periksa kembali data yang anda masukkan.',
                                    icon: 'error',
                                    confirmButtonColor: '#0F345E',
                                });
                            } else if (xhr.status === 417) {
                                Swal.fire({
                                    title: 'Import Data Keseluruhan Gagal',
                                    text: 'Data koleksi buku yang anda masukkan terdapat 0 data sesuai dan ' +
                                        xhr.responseJSON.fail_count +
                                        ' data tidak sesuai. Mohon periksa kembali data yang anda masukkan!',
                                    icon: 'warning',
                                    confirmButtonColor: '#0F345E',
                                }).then((result) => {
                                    window.location.href =
                                        '/book-list/import-excel';
                                });
                            } else if (xhr.status === 419) {
                                Swal.fire({
                                    title: 'Import Data Sebagian Berhasil',
                                    text: 'Data koleksi buku yang anda masukkan terdapat ' +
                                        xhr.responseJSON.success_count +
                                        ' data sesuai dan ' + xhr
                                        .responseJSON.fail_count +
                                        ' data tidak sesuai. Mohon periksa kembali data yang anda masukkan!',
                                    icon: 'warning',
                                    confirmButtonColor: '#0F345E',
                                }).then((result) => {
                                    window.location.href =
                                        '/book-list/import-excel';
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: 'Import Data Gagal.',
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
