<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::with(['roles', 'permissions'])->whereNotIn('name', ['Admin',])->get();
        $role = Role::whereNotIn('name', ['Pemustaka', 'Kepala Perpustakaan'])->get();

        return view('pages.user.index', [
            'title' => 'Kelola Pengguna',
            'data' => $data,
            'role' => $role
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
        try {
            $validation = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'min:3',
                    'regex:/^[a-zA-Z][a-zA-Z\s]*$/',
                    'unique:users',
                    'max:255'
                ],
                'number_id' => [
                    // 'required',
                    'string',
                    'min:3',
                    'regex:/^[1-9][0-9]*\.?[0-9]*$/',
                    'unique:users',
                    'max:255'
                ],
                'role' => [
                    'required',
                    'integer',
                    Rule::exists('roles', 'id'),
                ],
            ], $this->messageValidation(), $this->attributeValidation());

            $validation['email'] = strtolower(str_replace(' ', '', $validation['name'])) . '2023@gmail.com';
            $validation['password'] = bcrypt(str_replace(' ', '', $validation['name']) . '@2023');
            $validation['number_id'] = $this->generateRandomNumberId();

            $user = User::create($validation);
            $user->givePermissionTo('Aktif');

            $roleId = $request->input('role');
            $user->roles()->attach($roleId);

            if ($user) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Registration for the new employee candidate was successful'
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to register the new employee candidate'
                ], 500);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);

            $data = User::findOrFail($id);

            return view('pages.user.show', [
                'title' => 'Detail Pengguna',
                'data' => $data
            ]);
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::findOrFail($id);

        return response()->json([
            'data' => $data,
        ]);
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

    public function toggleStatus(User $user)
    {
        $permissionName = 'Aktif';
        $status = '';

        if ($user->hasPermissionTo($permissionName)) {
            $user->syncPermissions('Tidak Aktif');
            $status = 'Tidak Aktif';
        } else {
            $user->syncPermissions([$permissionName]);
            $status = 'Aktif';
        }

        return response()->json([
            'status' => $status,
        ]);
    }

    public function toggleRole(User $user)
    {
        $permissionName = 'Pustakawan';
        $status = '';

        if ($user->hasRole($permissionName)) {
            $user->syncRoles('Penerbit');
            $status = 'Penerbit';
        } else {
            $user->syncRoles([$permissionName]);
            $status = 'Pustakawan';
        }

        return response()->json([
            'role' => $status,
        ]);
    }

    public function updatePassword(Request $request, string $id)
    {
        try {
            $request->validate([
                'password_new' => 'required|string|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*()-_=+])[A-Za-z\d!@#$%^&*()-_=+]{8,}$/',
                'confirm_password' => 'required|same:password_new',
            ], $this->messageValidation());
            $user = User::findOrFail($id);

            $user->update([
                'password' => Hash::make($request->password_new),
            ]);

            return response()->json(['message' => 'Password updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while changing the password.'], 500);
        }
    }

    private function messageValidation()
    {
        $message = [
            'required' => ':attribute harus diisi.',
            'string' => ':attribute harus berupa teks.',
            'name.regex' => ':attribute harus berupa karakter huruf.',
            'unique' => ':attribute sudah digunakan.',
            'min' => ':attribute minimal :min karakter.',
            'max' => ':attribute maksimal :max karakter.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'password_new.required' => 'Kata Sandi Baru wajib diisi.',
            'password_new.string' => 'Kata Sandi Baru harus berupa teks.',
            'password_new.min' => 'Kata Sandi Baru minimal terdiri dari 8 karakter.',
            'password_new.regex' => 'Kata Sandi Baru harus mengandung setidaknya satu huruf, satu angka, dan satu simbol.',
            'confirm_password.required' => 'Konfirmasi Kata Sandi wajib diisi.',
            'confirm_password.same' => 'Konfirmasi Kata Sandi harus sama dengan Kata Sandi Baru.',
        ];

        return $message;
    }

    private function attributeValidation()
    {
        $customAttributes = [
            'name' => 'Nama',
            'role' => 'Peran',
            'number_id' => 'ID Anggota'
        ];

        return $customAttributes;
    }

    private function generateRandomNumberId()
    {
        $isUnique = false;
        $maxAttempts = 18;
        $attempts = 0;

        while (!$isUnique && $attempts < $maxAttempts) {
            $randomNumber = $this->generateRandomSuffix();

            if (!$this->numberIdExists($randomNumber)) {
                $isUnique = true;
                return '192' . $randomNumber;
            }

            $attempts++;
        }

        throw new \RuntimeException('Unable to generate a unique random number_id.');
    }

    private function generateRandomSuffix()
    {
        return mt_rand(100000000000000, 999999999999999);
    }

    private function numberIdExists($number)
    {
        return DB::table('users')->where('number_id', $number)->exists();
    }
}
