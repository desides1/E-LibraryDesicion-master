<form action="{{ url('/front-request') }}">
    <div class="text-center position-relative pb-3 my-4 mx-auto" style="max-width: 750px;">
        <div class="input-group mb-3">
            <button class="btn dropdown-toggle" id="dropdownMenuButtons" type="button" data-bs-toggle="dropdown"
                aria-expanded="false"
                style="border-top-left-radius: 10px; border-bottom-left-radius: 10px; border: 1px solid #ced4da">Kategori</button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" data-type="text" name="search-title"
                        data-name="Judul">Judul</a></li>
                <li><a class="dropdown-item" href="#" data-type="text" name="search-isbn"
                        data-name="ISBN">ISBN</a></li>
                <li><a class="dropdown-item" href="#" data-type="number" name="search-year"
                        data-name="Tahun Terbit">Tahun Terbit</a></li>
            </ul>
            <input type="text" class="form-control py-2" placeholder="Cari Referensi Buku..."
                aria-label="Recipient's username" aria-describedby="button-addon2" name="search" id="cariInput"
                value="{{ request('search') }}">
            <button class="btn btn-outline-primary me-3" type="submit" id="button-addon2"
                style="border-top-right-radius: 10px; border-bottom-right-radius: 10px">Cari</button>
            <button class="btn btn-outline-secondary" type="button" id="button-create-book"
                onclick="window.location.href='{{ url('/front-requests') }}'" style="border-radius: 10px">Usulan
                Baru</button>
        </div>
    </div>
</form>

@push('scripts')
    <script defer>
        $(document).ready(function() {
            $('.dropdown-item').on('click', function(event) {
                event.preventDefault();
                $('.dropdown-item').removeClass('active');
                $(this).addClass('active');

                var text = $(this).text();
                var type = $(this).data('type');
                var name = $(this).attr('name');

                $('#dropdownMenuButtons').text(text);
                $('#cariInput').attr('placeholder', 'Cari Referensi Berdasarkan ' + text + ' Buku...');
                $('#cariInput').attr('type', type);
                $('#cariInput').attr('name', name);
                $('#cariInput').val('');
            });

            $('#button-addon2').on('click', function(event) {
                var selectedCategory = $('.dropdown-toggle').text().trim();
                if (selectedCategory === 'Kategori') {
                    event.preventDefault();
                    alert('Silakan pilih kategori pencarian terlebih dahulu.');
                }
            });
        });
    </script>
@endpush
