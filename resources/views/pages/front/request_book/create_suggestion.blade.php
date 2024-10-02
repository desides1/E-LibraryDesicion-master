<form id="formSuggestion" enctype="multipart/form-data" class="p-2 mt-5" style="display: none">
    <div>
        <h5 class="fw-bolder text-dark mb-2">A. Identitas Pemustaka</h5>
        <div class="mb-3 mt-4 row">
            <label for="staticEmail" class="col-sm-3 col-form-label">Nama</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="staticEmail" value="{{ old('name') }}" name="name" autofocus
                    placeholder="Masukkan Nama Lengkap Anda" oninput="(() => { restrictInputName(this); })()" required>
            </div>
        </div>
        <div class="mb-3 mt-3 row">
            <label for="staticEmail" class="col-sm-3 col-form-label">Status Pemustaka</label>
            <div class="col-sm-9 d-flex">
                <div class="form-check me-3">
                    <input class="form-check-input" type="radio" name="status" id="Dosen" value="Dosen"
                        {{ old('status' == 'Dosen' ? 'checked' : '') }}>
                    <label class="form-check-label" for="Dosen">
                        Dosen
                    </label>
                </div>
                <div class="form-check me-3">
                    <input class="form-check-input" type="radio" name="status" id="Mahasiswa" value="Mahasiswa"
                        {{ old('status' == 'Mahasiswa' ? 'checked' : '') }}>
                    <label class="form-check-label" for="Mahasiswa">
                        Mahasiswa
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="Karyawan" value="Karyawan"
                        {{ old('status' == 'Karyawan' ? 'checked' : '') }}>
                    <label class="form-check-label" for="Karyawan">
                        Karyawan
                    </label>
                </div>
            </div>
        </div>
        <div class="mb-3 row" id="nimValid" style="display: none">
            <label for="inputNumber" class="col-sm-3 col-form-label" id="labelNIM">NIM</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="inputNumber" name="number_id"
                    value="{{ old('number_id') }}" placeholder="Masukkan NIM Anda"
                    oninput="validateInput(event)">
            </div>
        </div>
        <div class="mb-3 row" id="majorValid" style="display: none">
            <label for="inputMajor" class="col-sm-3 col-form-label" id="labelMajor">Program Studi</label>
            <div class="col-sm-9" id="majorData">
                <select name="major" id="inputMajor" class="form-select selectMajors">
                    <option value="">Pilih Program Studi</option>
                    @foreach ($major as $item)
                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 row" id="unitValid" style="display: none">
            <label for="inputUnit" class="col-sm-3 col-form-label" id="labelUnit">Unit Poliwangi</label>
            <div class="col-sm-9" id="unitData">
                <select name="unit" id="inputUnit" class="form-select selectMajors">
                    <option value="">Pilih Unit Poliwangi</option>
                    @foreach ($unit as $item)
                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h5>B. Pengajuan Buku</h5>
        <div class="mb-3 mt-4 row">
            <label for="titleBook" class="col-sm-3 col-form-label">Judul Buku</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="titleBook" value="{{ old('title') }}"
                    name="title" required>
            </div>
        </div>
        <div class="mb-3 mt-4 row">
            <label for="isbnBook" class="col-sm-3 col-form-label">ISBN</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="isbnBook" name="isbn" required
                    oninput="(() => { restrictNumbers(this); })()">
            </div>
        </div>
        <div class="mb-3 mt-4 row">
            <label for="authorBook" class="col-sm-3 col-form-label">Penulis</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="authorBook" value="{{ old('author') }}" name="author"
                    required oninput="(() => { restrictInputName(this); })()">
            </div>
        </div>
        <div class="mb-3 mt-4 row">
            <label for="publisherBook" class="col-sm-3 col-form-label">Penerbit</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="publisherBook" name="publisher"
                    value="{{ old('publisher') }}" required oninput="(() => { restrictInput(this); })()">
            </div>
        </div>
        <div class="mb-3 mt-4 row">
            <label for="publicationDate" class="col-sm-3 col-form-label">Tahun Terbit</label>
            <div class="col-sm-9">
                <input type="number" class="form-control" id="publicationDate" name="publication_date"
                    value="{{ old('publication_date') }}" required oninput="(() => { restrictNumberPn(this); })()">
            </div>
        </div>
        <div class="mb-3 mt-4 row">
            <label for="categoryBook" class="col-sm-3 col-form-label">Bidang Ilmu</label>
            <div class="col-sm-9">
                <select class="form-select js-example-basic-single" name="category_id" id="category_id" required>
                    <option selected disabled>Pilih jenis Klasifikasi buku</option>
                    @foreach ($category as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name }} ({{ $item->code }})</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 mt-4 row">
            <label for="priceBook" class="col-sm-3 col-form-label">Harga Buku</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="priceBook" alue="{{ old('price') }}"
                    name="price" required oninput="(() => { restrictNumber(this); })()">
            </div>
        </div>
        <div class="mb-3 mt-4 row">
            <label for="stockBook" class="col-sm-3 col-form-label">Stok Buku</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="stockBook" value="{{ old('available_stock') }}"
                    name="available_stock" required oninput="(() => { restrictNumber(this); })()">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="type_book" class="col-sm-3 col-form-label">Jenis Buku</label>
            <div class="col-sm-9 d-flex">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type_book" id="flexRadioDefault1"
                        value="E-Book">
                    <label class="form-check-label" for="flexRadioDefault1">
                        E-Book
                    </label>
                </div>
                <div class="form-check ms-3">
                    <input class="form-check-input" type="radio" name="type_book" id="flexRadioDefault2"
                        value="Cetak" checked>
                    <label class="form-check-label" for="flexRadioDefault2">
                        Cetak
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="float-end my-3">
        <button type="button" class="btn btn-danger" id="btnBatal" style="border-radius: 5px;">Batal</button>
        <button type="submit" class="btn btn-primary ms-2" style="border-radius: 5px;">Kirim Usulan Buku</button>
    </div>
</form>
