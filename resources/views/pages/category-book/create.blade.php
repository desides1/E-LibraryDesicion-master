<div class="modal fade" id="userCreateModal" tabindex="-1" role="dialog" aria-labelledby="userCreateModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userCreateModalTitle">Tambah Klasifikasi Buku</h5>
            </div>
            <form action="" id="formInsert">
                <div class="modal-body">
                    <label for="userName" id="username-create">Kode Klasifikasi Buku: </label>
                    <div class="form-group">
                        <input id="userName" type="text" class="form-control" name="code"
                            placeholder="Masukkan Nama Kode Klasifikasi Buku" required
                            oninput="(() => { validateSpaceInput(this); })()">
                    </div>
                    <label for="userName" id="username-create">Nama Klasifikasi Buku: </label>
                    <div class="form-group">
                        <input id="userName" type="text" class="form-control" name="name"
                            placeholder="Masukkan Nama Klasifikasi Buku" required
                            oninput="(() => { restrictInput(this); })()">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                    <button type="submit" class="btn btn-outline-primary ms-1" id="create-modal">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
