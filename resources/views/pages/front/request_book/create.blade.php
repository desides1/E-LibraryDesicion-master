<form id="formInsert" enctype="multipart/form-data" class="p-2">
    <div>
        <h5 class="fw-bolder text-dark mb-2">A. Identitas Pemustaka</h5>
        <div class="mb-3 mt-4 row">
            <label for="staticEmail" class="col-sm-3 col-form-label">Nama</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="staticEmail" value="{{ old('name') }}" name="name"
                    placeholder="Masukkan Nama Lengkap Anda" oninput="(() => { restrictInputName(this); })()" required>
                <input type="hidden" name="idBook" id="id">
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
                <select name="major" id="inputMajor" class="form-select selectMajor">
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
                <select name="unit" id="inputUnit" class="form-select selectMajor">
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
                <input type="text" class="form-control" id="titleBook" value="" readonly>
            </div>
        </div>
        <div class="mb-3 mt-4 row">
            <label for="isbnBook" class="col-sm-3 col-form-label">ISBN</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="isbnBook" value="" readonly>
            </div>
        </div>
        <div class="mb-3 mt-4 row">
            <label for="authorBook" class="col-sm-3 col-form-label">Penulis</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="authorBook" value="" readonly>
            </div>
        </div>
        <div class="mb-3 mt-4 row">
            <label for="publisherBook" class="col-sm-3 col-form-label">Penerbit</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="publisherBook" value="" readonly>
            </div>
        </div>
        <div class="mb-3 mt-4 row">
            <label for="publicationDate" class="col-sm-3 col-form-label">Tahun Terbit</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="publicationDate" value="" readonly>
            </div>
        </div>
        <div class="mb-3 mt-4 row">
            <label for="categoryBook" class="col-sm-3 col-form-label">Bidang Ilmu</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="categoryBook" value="" readonly>
            </div>
        </div>
        <div class="mb-3 mt-4 row">
            <label for="priceBook" class="col-sm-3 col-form-label">Harga Buku</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="priceBook" value="" readonly>
            </div>
        </div>
        <div class="mb-3 mt-4 row">
            <label for="stockBook" class="col-sm-3 col-form-label">Stok Buku</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="stockBook" value="" readonly>
            </div>
        </div>
    </div>
    <div class="float-end my-3">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
            style="border-radius: 5px;">Batal</button>
        <button type="submit" class="btn btn-primary ms-2" style="border-radius: 5px;">Kirim Usulan Buku</button>
    </div>
</form>
