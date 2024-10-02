<script defer>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });

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


    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var rowNumber = 1;

        $('#category_id').change(function() {
            function formatPrice(price) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(price).replace(/\D00$/, '');
            }

            var selectedPrice = $('option:selected', this).data('price');
            var formattedPrice = formatPrice(selectedPrice).replace(/\s/g, '');

            var selectedTitle = $('option:selected', this).data('title');
            var selectedId = $('option:selected', this).data('id');

            $('#book-price').val(formattedPrice);
            $('#book-title').val(selectedTitle);
            $('#book-quantity').val(1);
            $('#book_id').val(selectedId);

            function updateResult() {
                var result = selectedPrice * parseInt($('#book-quantity').val() === '' ? 1 : $(
                        '#book-quantity')
                    .val());
                $('#book-result').val(formatPrice(result).replace(/\s/g, ''));
            }

            $('#book-result').val(formattedPrice);
            $('#book-quantity').on('input', updateResult);
        });

        $('#cancelButton').click(function() {
            $('#book-title').val('');
            $('#book-quantity').val('');
            $('#book-price').val('');
            $('#book-result').val('');
        });

        $('#addButton').click(function() {
            var title = $('#book-title').val();
            var quantity = $('#book-quantity').val();
            var price = $('#book-price').val();
            var result = $('#book-result').val();
            var id = $('#book_id').val();

            if (title.trim() === '' || quantity.trim() === '' || price.trim() === '' || result
                .trim() === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Harap isi semua kolom sebelum menambahkan data.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0F345E'
                });
                return;
            }

            var newRow = '<tr>' +
                '<td class="text-center">' + rowNumber + '</td>' +
                '<td>' + title + '</td>' +
                '<td>' + quantity + '</td>' +
                '<td>' + price + '</td>' +
                '<td>' + result + '</td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger" onclick="deleteRow(this)">Hapus</button></td>' +
                '<td style="display:none">' + id + '</td>' +
                '</tr>';

            rowNumber++;

            $('#book-table tbody').append(newRow);

            updateTotal();

            $('#book-title').val('');
            $('#book-quantity').val('');
            $('#book-price').val('');
            $('#book-result').val('');

            toggleTableVisibility();
        });

        $('#saveData').on('click', function() {
            var data = [];

            $('#book-table tbody tr').each(function() {
                var rowData = {
                    'book_id': $(this).find('td:eq(6)').text(),
                    'quantity': $(this).find('td:eq(2)').text(),
                    'price': $(this).find('td:eq(3)').text().replace('Rp', '').replace('.',
                        ''),
                    'result': $(this).find('td:eq(4)').text().replace('Rp', '').replace('.',
                        ''),
                };

                data.push(rowData);
            });

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
                        type: 'POST',
                        url: '/book-purchase',
                        data: {
                            'data': data,
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Data pengajuan pengadaan buku berhasil ditambahkan',
                                icon: 'success',
                                confirmButtonColor: '#0F345E',
                            }).then((result) => {
                                window.location.href = '/book-history';
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Input Tidak Valid',
                                text: 'Terjadi kesalahan saat menambahkan pengadaan buku. Mohon periksa kembali data yang Anda masukkan',
                                icon: 'error',
                                confirmButtonColor: '#0F345E',
                            });
                        }
                    });
                }
            });
        });
    });

    function deleteRow(button) {
        $(button).closest('tr').remove();
        updateTotal();
        toggleTableVisibility();
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(price).replace(/\D00$/, '');
    }

    function updateTotal() {
        var total = 0;

        $('#book-table tbody tr').each(function() {
            var resultText = $(this).find('td:eq(4)').text();

            var cleanedResultText = resultText.replace(/[^\d]/g, '');
            var result = parseFloat(cleanedResultText);

            total += isNaN(result) ? 0 : result;
        });

        var formattedTotal = total.toLocaleString('id-ID', {
            style: 'currency',
            currency: 'IDR'
        });
        formattedTotal = formattedTotal.replace(/\s/g, '').replace(/,00$/, '');

        $('#result-price').text(formattedTotal);

        var budgetPrice = {{ $budget->price }};
        var saveDataButton = $('#saveData');

        if (total <= budgetPrice) {
            $('#price-validation').removeClass('btn btn-danger').addClass('btn btn-success').text('Sesuai anggaran');
            saveDataButton.show();
        } else {
            $('#price-validation').removeClass('btn btn-success').addClass('btn btn-danger').text('Melebihi anggaran');
            saveDataButton.hide();
        }
    }


    function toggleTableVisibility() {
        var tableDataDiv = $('#table-data');
        var table = $('#book-table');

        if (table.find('tbody tr').length > 0) {
            tableDataDiv.show();
        } else {
            tableDataDiv.hide();
        }
    }
</script>
