<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::where('id', '=', auth()->user()->id)->get();

        return view('pages.profile.index', [
            'title' => 'Profil',
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'password_old' => 'required|min:8',
                'password_new' => 'required|min:8|different:password_old|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                'confirm_password' => 'required|same:password_new',
            ], $this->messageValidation(), $this->attribuateValidation());

            if (Hash::check($request->password_old, Auth::user()->password)) {
                $user = User::find(Auth::id());
                $user->password = Hash::make($request->password_new);
                $user->save();

                return response()->json(['success' => 'Password changed successfully!'], 201);
            } else {
                return response()->json(['error_old' => 'Incorrect old password.'], 424);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while changing the password.'], 500);
        }
    }

    private function messageValidation()
    {
        $mesaage = [
            'required' => ':attribute harus diisi.',
            'min' => ':attribute minimal :min karakter.',
            'regex' => ':attribute memerlukan huruf kapital, huruf kecil, angka, dan karakter khusus.',
            'passwordNew.different' => 'Kata Sandi Baru harus berbeda dari Kata Sandi Lama.',
            'same' => ':attribute harus sama dengan Kata Sandi Baru.',
        ];

        return $mesaage;
    }

    private function attribuateValidation()
    {
        $attributes = [
            'password_old' => 'Kata Sandi Lama',
            'password_new' => 'Kata Sandi Baru',
            'confirm_password' => 'Konfirmasi Kata Sandi',
        ];

        return $attributes;
    }
}
