<form class="form form-horizontal" id="formInsert-temporary" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="category_id" class="form-label">Rekomendasi Pengadaan Buku</label>
                <select class="js-example-basic-single" name="category_id" id="category_id" style="width: 100% !important"
                    autofocus required>
                    <option selected disabled>Pilih Rekomendasi Pengadaan Buku</option>
                    @foreach ($data as $item)
                        <option value="{{ $item['publisher_id'] }}" data-price="{{ $item['book-price'] }}"
                            data-title="{{ $item['title'] }}" data-id="{{ $item['publisher_id'] }}">
                            {{ $item['title'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <label for="book-summary" class="form-label">Jumlah Anggaran Pengadaan Buku</label>
            <input type="text" class="form-control" id="book-summary" aria-describedby="book-summary" readonly
                style="text-align: left" name="summary"
                value="{{ old('summary', 'Rp' . number_format($budgetProdi, 0, ',', '.')) }}">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3 ">
                <label for="book-title" class="form-label">Judul Buku</label>
                <input type="text" class="form-control" id="book-title" aria-describedby="book-title" readonly
                    style="text-align: left" name="book_title">
                <input type="hidden" name="publisher_id" id="publisher_id">
            </div>
            <div class="mb-3">
                <label for="book-price" class="form-label">Harga Buku</label>
                <input type="text" class="form-control" id="book-price" aria-describedby="book-price"
                    name="book_price" readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="book-quantity" class="form-label">Jumlah Buku</label>
                <input type="text" class="form-control" id="book-quantity"
                    placeholder="Masukkan jumlah buku yang akan dibeli" aria-describedby="book-quantity"
                    name="book_quantity" required oninput="(() => { restrictNumber(this); })()">
            </div>
            <div class="mb-3">
                <label for="book-result" class="form-label">Total Harga</label>
                <input type="text" class="form-control" id="book-result" aria-describedby="book-result"
                    name="book_result" readonly>
            </div>
        </div>
    </div>
    <div class="col-sm-12 d-flex justify-content-end mt-2">
        <button type="button" id="cancelButton" class="btn btn-outline-dark me-2 mb-1">Reset</button>
        <button type="button" class="btn btn-outline-primary mb-1" id="addButton">Tambah</button>
    </div>
</form>

<form class="form form-horizontal" id="formInsert" enctype="multipart/form-data">
    <div class="mt-3 table-responsive" id="table-data" style="display: none">
        <p class="fw-bolder text-dark mb-3">Tabel Pengajuan Pengadaan Buku Pada Perpustakaan Politeknik
            Negeri
            Banyuwangi
        </p>
        <table class="table table-bordered" id="book-table">
            <thead class="table-light">
                <tr>
                    <th class="text-center">No</th>
                    <th>Judul Buku</th>
                    <th>Jumlah Buku</th>
                    <th>Harga Buku</th>
                    <th>Total Harga</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <input type="hidden" name="publisher_id[]">
                <input type="hidden" name="book_quantity[]">
                <input type="hidden" name="book_price[]">
                <input type="hidden" name="book_result[]">
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end">Total Keseluruhan Pengadaan</td>
                    <td colspan="1" id="result-price"></td>
                    <td colspan="1" class="text-center"><button id="price-validation" type="button"></button></td>
                </tr>
            </tfoot>
        </table>
        <div class="col-sm-12 d-flex justify-content-end mt-4">
            <button type="button" class="btn btn-primary mb-1 me-1" id="saveData">Simpan</button>
        </div>
    </div>
</form>
