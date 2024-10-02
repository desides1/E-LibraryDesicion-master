<form class="form form-horizontal" id="formInsert" enctype="multipart/form-data">
    <div class="mb-3 row">
        <label for="title" class="col-sm-2 col-form-label">Nama Buku</label>
        <div class="col-sm-10">
            {{-- <label for="title" class="col-sm-12 col-form-label text-danger fw-bold">
                Pilih sumber buku dari koleksi perpustakaan atau penerbit untuk pengadaan, tapi hanya boleh memilih
                salah satu saja!!!
            </label> --}}

            {{-- <div class="mb-1">
                <label for="title" class="col-sm-6 col-form-label">Koleksi Buku Perpustakaan</label>
                <a href="/user-book" class="float-end text-danger fw-bolder">Hapus</a>
            </div> --}}
            <select class="choices form-select" name="book_id">
                <option selected value="0">Pilih judul buku dari koleksi buku perpustakaan atau penerbit</option>
                @foreach ($book_library as $book)
                    <option value="{{ $book->id }}">
                        {{ $book->title }} ({{ \Carbon\Carbon::parse($book->publication_date)->format('Y') }})
                        (Perpustakaan)
                    </option>
                @endforeach
                @foreach ($book_publisher as $book)
                    <option value="{{ $book->id }}">
                        {{ $book->title }} ({{ \Carbon\Carbon::parse($book->publication_date)->format('Y') }})
                        (Penerbit)
                    </option>
                @endforeach
            </select>

            {{-- <div class="mb-1">
                <label for="title" class="col-sm-6 col-form-label">Koleksi Buku Penerbit</label>
                <a href="/user-book" class="float-end text-danger fw-bolder">Hapus</a>
            </div>
            <select class="choices form-select" name="publishers_id">
                <option selected value="0">Pilih nama buku dari koleksi penerbit</option>
                @foreach ($publisher as $publisher)
                    <option value="{{ $publisher->id }}">
                        {{ $publisher->title }} ({{ \Carbon\Carbon::parse($publisher->publication_date)->format('Y') }})
                    </option>
                @endforeach
            </select> --}}
        </div>
    </div>

    <div class="col-sm-12 d-flex justify-content-end mt-3">
        <button type="button" id="cancelButton" class="btn btn-outline-dark me-2 mb-1">Batal</button>
        <button type="submit" class="btn btn-outline-primary mb-1">Simpan</button>
    </div>
</form>
