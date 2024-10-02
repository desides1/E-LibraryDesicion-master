@push('scripts')
    <script defer>
        $(document).ready(function() {
            $('.js-example-basic-singles').select2();
        });

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var rowNumber = 1;

            // $('#manageBook').click(function(event) {
            $('#table12').on('click', '#manageBook', function(event) {
                event.preventDefault();

                var budgetProdi = "{{ $budgetProdi }}";
                var remainderPay = "{{ $budgetProdi - $paymentBook }}";

                if (budgetProdi > 0 && remainderPay > 0) {
                    $('#purchaseBook').modal('show');
                } else if (budgetProdi == 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tindakan Ini Tidak Dapat Dilakukan',
                        text: 'Program Studi tidak memiliki anggaran untuk pengadaan buku.'
                    });
                } else if (remainderPay <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tindakan Ini Tidak Dapat Dilakukan',
                        text: 'Program Studi Sudah Mencapai Batas Anggaran Pengadaan Buku.'
                    });
                }

                function formatPrice(price) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(price).replace(/\D00$/, '');
                }

                var selectedPrice = $(this).data('price');
                var formattedPrice = formatPrice(selectedPrice).replace(/\s/g, '');

                var selectedTitle = $(this).data('title');
                var selectedId = $(this).data('id');
                var selectedStock = $(this).data('stocks');

                $('#book-price').val(formattedPrice);
                $('#book-title').val(selectedTitle);
                $('#book-quantity').val(1);
                $('#publisher_id').val(selectedId);

                const restrictNumber = (inputElement) => {
                    inputElement.addEventListener("input", () => {
                        let inputText = inputElement.value;

                        inputText = inputText.replace(/[^0-9]/g, '').replace(/^[^1-9]0/, '0');

                        inputText = inputText.replace(/\.+/g, '.');

                        if (inputText.length > 0 && inputText[0] === ' ') {
                            inputText = inputText.trim();
                        }

                        inputText = inputText.replace(/^0+/, '');

                        const inputValue = parseInt(inputText);

                        if (inputValue > selectedStock) {
                            inputText = selectedStock.toString();
                        }

                        inputElement.value = inputText;
                    });
                };

                restrictNumber(document.getElementById('book-quantity'));

                function updateResult() {
                    var result = selectedPrice * parseInt($('#book-quantity').val() === '' ? 1 : $(
                            '#book-quantity')
                        .val());
                    $('#book-result').val(formatPrice(result).replace(/\s/g, ''));
                }

                $('#book-result').val(formattedPrice);
                $('#book-quantity').on('input', updateResult);

                $('#formInsert-temporary').show();
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
                var id = $('#publisher_id').val();

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

                var rowCount = $('#book-table tbody tr').length;
                var existingRow = $('#book-table tbody').find('td:nth-child(2):contains("' + title + '")')
                    .closest('tr');

                if (existingRow.length > 0) {
                    var existingQuantity = parseInt(existingRow.find('td:nth-child(3)').text());
                    var newQuantity = existingQuantity + parseInt(quantity);
                    existingRow.find('td:nth-child(3)').text(newQuantity);

                    var existingPriceText = existingRow.find('td:nth-child(4)').text();
                    var existingPriceNumber = parseInt(existingPriceText.replace(/\D/g, ''));
                    var newResult = existingPriceNumber * newQuantity;
                    existingRow.find('td:nth-child(5)').text(formatPrice(newResult).replace(/\s/g, ''));
                } else {
                    var rowCount = $('#book-table tbody tr').length;

                    var newRow = '<tr>' +
                        '<td class="text-center">' + (rowCount + 1) + '</td>' +
                        '<td>' + title + '</td>' +
                        '<td>' + quantity + '</td>' +
                        '<td>' + price + '</td>' +
                        '<td>' + result + '</td>' +
                        '<td class="text-center"><button type="button" class="btn btn-outline-danger" onclick="deleteRow(this)">Hapus</button></td>' +
                        '<td style="display:none">' + id + '</td>' +
                        '</tr>';

                    $('#book-table tbody').append(newRow);
                }

                updateTotal();

                $('#book-title').val('');
                $('#book-quantity').val('');
                $('#book-price').val('');
                $('#book-result').val('');

                $('#formInsert-temporary').hide();

                toggleTableVisibility();
            });

            $('#saveData').on('click', function() {
                var data = [];
                const datasave = "{{ encrypt($budget->id) }}";

                $('#book-table tbody tr').each(function() {
                    var rowData = {
                        'major_id': $('#majorName').val(),
                        'publisher_id': $(this).find('td:eq(6)').text(),
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
                            url: "{{ url('/book-budget/payment') }}",
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
                                    window.location.href =
                                        "{{ url('/book-budget') }}/" + datasave;
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
            updateRowNumbers();
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
                ppn = ({{ $budget->ppn / 100 }} * total) + total;
            });

            var formattedTotal = total.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });
            formattedTotal = formattedTotal.replace(/\s/g, '').replace(/,00$/, '');

            var formattedPpn = ppn.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });
            formattedPpn = formattedPpn.replace(/\s/g, '').replace(/,00$/, '');

            $('#result-price').text(formattedTotal);
            $('#result-price-ppn').text(formattedPpn);

            var budgetPrice = "{{ $budgetProdi - $ppnPayment }}";
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

        function updateRowNumbers() {
            $('#book-table tbody tr').each(function(index, row) {
                $(row).find('td:first').text(index + 1);
            });
        }
    </script>
@endpush
