@push('scripts')
    <script defer>
        $(document).ready(() => {
            const addedPublisherIds = [];
            var rowNumber = 1;

            $('#table12').on('click', '#btn-add-book', function() {
                var publisherId = $(this).data('id');
                var row = $(this).closest('tr');

                $(this).removeClass('btn-outline-success').addClass('btn-outline-primary');
                $(this).prop('disabled', true);

                addedPublisherIds.push(publisherId);

                var title = row.find('.book-title').text();
                var price = row.find('.book-price').text();
                var requestBook = row.find('.book-request').text();
                var id = row.find('.publisher_id').text();

                console.log(title, price, requestBook, id);

                var newRow = '<tr>' +
                    '<td class="text-center">' + rowNumber + '</td>' +
                    '<td>' + title + '</td>' +
                    '<td>' + requestBook + '</td>' +
                    '<td>' + price + '</td>' +
                    '<td class="text-center"><button type="button" class="btn btn-outline-danger" onclick="deleteRow(this)">Hapus</button></td>' +
                    '<td style="display:none">' + id + '</td>' +
                    '</tr>';

                rowNumber++;
                $('#table6 tbody').append(newRow);

                updateDeleteButtonState();
                updateResultText();
            });

            $('#table12').on('click', '.user-detail-btn', function() {
                var publisherId = $(this).data('id');

                $.ajax({
                    url: "{{ url('/front-request-book') }}/" + publisherId,
                    type: 'GET',
                    success: function(response) {
                        $('#id').val(publisherId);
                        $('#titleBook').val(response.title);
                        $('#isbnBook').val(response.isbn);
                        $('#authorBook').val(response.author);
                        $('#publisherBook').val(response.publisher);
                        $('#publicationDate').val(response.publication);
                        $('#categoryBook').val(response.category);
                        $('#priceBook').val(response.price);
                        $('#stockBook').val(response.stock);

                        var imageUrl = response.image;
                        $('#bookImage').attr('src', imageUrl);
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
                        $('#bookImage').val('');
                    }
                });

                $('#detailRank').modal('show');
            })

            $('#table12').on('click', '#btn-delete-book', function() {
                var selectedPublisherId = $(this).data('id');

                if (addedPublisherIds.includes(selectedPublisherId)) {

                    var index = addedPublisherIds.indexOf(selectedPublisherId);
                    if (index !== -1) {
                        addedPublisherIds.splice(index, 1);
                    }

                    $('#btn-add-book[data-id="' + selectedPublisherId + '"]')
                        .removeClass('btn-outline-primary').addClass('btn-outline-success')
                        .prop('disabled', false);

                    updateDeleteButtonState();
                    updateResultText();
                }
            });

            $('#btn-save-book').on('click', function() {
                if (addedPublisherIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tindakan Tidak Dapat Dilakukan',
                        text: 'Anda harus menambahkan atau memilih judul buku.'
                    });
                } else {
                    $('#purchaseBook').modal('show');
                }
            });
            const updateDeleteButtonState = () => {
                $('#btn-delete-book').prop('disabled', addedPublisherIds.length > 0 ? false : true);
            };

            const updateResultText = () => {
                $('#result_book_request').text(`Jumlah Pengadaan Buku: ${addedPublisherIds.length} Buku`);
            };
        });
    </script>
@endpush
