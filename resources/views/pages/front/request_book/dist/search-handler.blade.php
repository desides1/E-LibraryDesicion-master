@push('scripts')
    <script defer>
        const restrictInputSearch = (inputElement) => {
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

        $(document).ready(function() {
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data(page);
            });

            function fetch_data(page) {
                var id = $('#publisher').data('id');

                $.ajax({
                    url: "{{ url('/front-request-user/') }}" + id + "?page=" + page,
                    success: function(data) {
                        $('.list-user').html(data);
                    }
                })
            }

            $(document).on('keyup', '#name', function() {
                searchData();
            });

            function searchData() {
                var name = $('#name').val();
                var id = $('#publisher').data('id');

                $.ajax({
                    url: "{{ url('/front-request-user/') }}" + id,
                    type: "GET",
                    data: {
                        name: name,
                    },
                    success: function(data) {
                        if (data.hasOwnProperty('error')) {
                            $('.list-user').html('<h5>' + data.error + '</h5>');
                        } else {
                            $('.list-user').html(data);
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 404) {
                            $('.list-user').html(
                                '<p class="text-danger">Data yang Anda cari tidak ditemukan.</p>');
                        }
                    }
                });
            }
        });
    </script>
@endpush
