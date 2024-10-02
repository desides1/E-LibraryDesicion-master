<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login', [
            'title' => 'Login',
        ]);
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'credential' => 'required',
                'password' => 'required|min:8',
            ], [
                'credential.required' => 'Email atau ID Anggota harus diisi.',
                'password.required' => 'Kata Sandi harus diisi.',
                'password.min' => 'Kata Sandi minimal :min karakter.',
            ]);

            if ($validator->fails()) {
                return redirect()->route('login')
                    ->withErrors($validator)
                    ->withInput(['credential' => $request->input('credential')]);
            }

            $credentials = $request->only(['credential', 'password']);

            if (filter_var($credentials['credential'], FILTER_VALIDATE_EMAIL)) {
                $field = 'email';
            } else {
                $field = 'number_id';
            }

            $user = User::where($field, $credentials['credential'])->first();

            if ($user && $user->can('Aktif') && Auth::attempt([$field => $credentials['credential'], 'password' => $credentials['password']])) {
                $request->session()->regenerate();

                return redirect(RouteServiceProvider::HOME);
            } elseif ($user && !$user->can('Aktif')) {
                session(['credential' => $credentials['credential']]);

                return redirect()->route('login')->with('error', 'Maaf, Anda tidak memiliki izin untuk masuk. Akun Anda tidak aktif. Silakan hubungi Admin.');
            } else {
                session(['credential' => $credentials['credential']]);

                return redirect()->route('login')->with('error', 'Email atau ID Anggota dan kata sandi yang Anda masukkan salah.');
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat mencoba masuk.');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
