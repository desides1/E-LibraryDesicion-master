@push('scripts')
    <script defer>
        $(document).ready(function() {
            $('.btn-usulkan').click(function() {
                var bookId = $(this).data('bookid');
                // console.log(bookId);
                $.ajax({
                    url: "{{ url('/front-request-book') }}/" + bookId,
                    type: 'GET',
                    success: function(response) {
                        $('#id').val(bookId);
                        $('#titleBook').val(response.title);
                        $('#isbnBook').val(response.isbn);
                        $('#authorBook').val(response.author);
                        $('#publisherBook').val(response.publisher);
                        $('#publicationDate').val(response.publication);
                        $('#categoryBook').val(response.category);
                        $('#priceBook').val(response.price);
                        $('#stockBook').val(response.stock);
                    },
                    error: function(xhr, status, error) {
                        $('#titleBook').val('');
                        $('#isbnBook').val('');
                        $('#authorBook').val('');
                        $('#publisherBook').val('');
                        $('#publicationDate').val('');
                        $('#categoryBook').val('');
                        $('#priceBook').val('');
                        $('#stockBook').val('');
                    }
                });
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#formInsert').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                if (Array.isArray(formData.getAll('major'))) {
                    formData.set('major', formData.getAll('major')[0]);
                }
                if (Array.isArray(formData.getAll('unit'))) {
                    formData.set('unit', formData.getAll('unit')[0]);
                }

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
                            url: "{{ url('/front-request') }}",
                            type: 'POST',
                            data: formData,
                            dataType: 'json',
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil',
                                    text: 'Data usulan buku baru berhasil ditambahkan.',
                                    icon: 'success',
                                    confirmButtonColor: '#0F345E',
                                }).then((result) => {
                                    window.location.href =
                                        "{{ url('/front-request') }}";
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
                                        text: 'Data usulan buku baru gagal ditambahkan.',
                                        icon: 'error',
                                        confirmButtonColor: '#0F345E',
                                    });
                                }
                            }
                        });
                    }
                });
            });

            $('#formSuggestion').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                if (Array.isArray(formData.getAll('major'))) {
                    formData.set('major', formData.getAll('major')[0]);
                }
                if (Array.isArray(formData.getAll('unit'))) {
                    formData.set('unit', formData.getAll('unit')[0]);
                }

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
                            url: "{{ url('/front-requests') }}",
                            type: 'POST',
                            data: formData,
                            dataType: 'json',
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil',
                                    text: 'Data usulan buku baru berhasil ditambahkan.',
                                    icon: 'success',
                                    confirmButtonColor: '#0F345E',
                                }).then((result) => {
                                    window.location.href =
                                        "{{ url('/front-request') }}";
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
                                        text: 'Data usulan buku baru gagal ditambahkan.',
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
