<div class="modal fade" id="userManageModal" tabindex="-1" role="dialog" aria-labelledby="userManageModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userManageModalTitle">Ubah Kata Sandi Pengguna</h5>
            </div>
            <form action="" id="formChangePassword">
                <div class="modal-body">
                    <label for="name">Nama: </label>
                    <div class="form-group">
                        <input type="hidden" name="id" id="id">
                        <input id="name" type="text" class="form-control" autofocus disabled required
                            name="name">
                    </div>
                    <label for="password_new">Kata Sandi Baru: </label>
                    <div class="form-group">
                        <input id="password_new" type="password" placeholder="Masukkan Kata Sandi" class="form-control"
                            autofocus required name="password_new" oninput="validateInput(this)">
                        <span toggle="#password_new" class="fa fa-fw fa-eye field-icon toggle-password"
                            onclick="togglePassword('password_new')"></span>
                    </div>
                    <label for="confirm_password">Konfirmasi Kata Sandi: </label>
                    <div class="form-group">
                        <input id="confirm_password" type="password" placeholder="Masukkan konfirmasi kata sandi"
                            class="form-control" required name="confirm_password" oninput="validateInput(this)">
                        <span toggle="#confirm_password" class="fa fa-fw fa-eye field-icon toggle-password"
                            onclick="togglePassword('confirm_password')"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                    <button type="submit" class="btn btn-outline-primary ms-1" id="update-modal">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
