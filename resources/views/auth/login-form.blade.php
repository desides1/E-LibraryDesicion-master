<form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="credential" class="form-label">Email atau ID Anggota</label>
        <input type="text" class="form-control" id="credential" name="credential"
            placeholder="Masukkan email atau id anggota" autofocus required
            value="{{ old('credential') ?: session('credential') }}" oninput="validateInput(this)" />
        @if ($errors->has('credential'))
            <label for="credential" class="text-danger error-message"
                style="text-align: justify;">{{ $errors->first('credential') }}</label>
        @endif
    </div>
    <div class="mb-3 form-password-toggle">
        <div class="d-flex justify-content-between">
            <label class="form-label" for="password">Kata Sandi</label>
            {{-- <a href="/forgot-password">
                <small>Forgot Password?</small>
            </a> --}}
        </div>
        <div class="input-group input-group-merge">
            <input type="password" id="password" class="form-control" name="password"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="password" required value="{{ old('password') }}" oninput="validateInput(this)" />
            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
        </div>
        @if ($errors->has('password'))
            <label for="password" class="text-danger error-message">{{ $errors->first('password') }}</label>
        @endif
    </div>
    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember-me" checked />
            <label class="form-check-label" for="remember-me"> Ingat Saya </label>
        </div>
    </div>
    <div class="mb-3">
        <button id="btnMasuk" class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
    </div>
</form>
