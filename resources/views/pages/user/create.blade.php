<div class="modal fade" id="userCreateModal" tabindex="-1" role="dialog" aria-labelledby="userCreateModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userCreateModalTitle">Tambah Pengguna</h5>
            </div>
            <form action="" id="formInsert">
                <div class="modal-body">
                    <label for="userName" id="username-create">Nama: </label>
                    <div class="form-group">
                        <input id="userName" type="text" class="form-control" name="name"
                            placeholder="Masukkan Nama Pengguna" required oninput="(() => { restrictInput(this); })()">
                    </div>
                    {{-- <label for="numberId" id="numberId-create">ID Anggota: </label>
                    <div class="form-group">
                        <input id="numberId" type="text" class="form-control" name="number_id"
                            placeholder="Masukkan ID Anggota" required oninput="(() => { restrictNumber(this); })()">
                    </div> --}}
                    <label for="role">Hak Akses: </label>
                    <div class="form-group">
                        <select class="choices form-select" name="role" required>
                            <option selected disabled>Pilih jenis hak akses</option>
                            @foreach ($role as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
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
