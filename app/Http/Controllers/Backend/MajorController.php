<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Major::orderBy('department', 'asc')->orderBy('name', 'asc')->get();

        return view('pages.major.index', [
            'title' => 'Program Studi',
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
        return view('pages.major.create', [
            'title' => 'Tambah Program Studi',
        ]);
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
                'name' => 'required|string|regex:/^[a-zA-Z0-9]+[a-zA-Z0-9 .,!-]*$/|max:255|unique:majors',
                'department' => 'required|string|regex:/^[a-zA-Z0-9]+[a-zA-Z0-9 .,!-]*$/|max:255',
            ], $this->messageValidation(), $this->attributeValidation());

            $validation['status'] = 'Aktif';

            $data = Major::create($validation);

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'The major has been successfully registered.'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to register the major.'
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

            $data = Major::findOrFail($id);

            return view('pages.major.show', [
                'title' => 'Detail Program Studi',
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
    public function edit($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);

            $data = Major::findOrFail($id);

            return view('pages.major.update', [
                'title' => 'Ubah Program Studi',
                'data' => $data
            ]);
        } catch (DecryptException $e) {
            abort(404);
        }
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
        try {
            $validation = $request->validate([
                'name' => 'required|string|regex:/^[a-zA-Z0-9]+[a-zA-Z0-9 .,!-]*$/|max:255|unique:majors,name,' . $id,
                'department' => 'required|string|regex:/^[a-zA-Z0-9]+[a-zA-Z0-9 .,!-]*$/|max:255',
            ], $this->messageValidation(), $this->attributeValidation());

            $data = Major::findOrFail($id)->update($validation);

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'The major has been successfully updated.'
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update the major.'
                ], 500);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
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

    public function toggleStatus(Major $major)
    {
        $major->status = ($major->status === 'Aktif') ? 'Tidak Aktif' : 'Aktif';
        $major->save();

        return response()->json([
            'status' => $major->status
        ]);
    }

    private function messageValidation()
    {
        $message = [
            'required' => ':attribute harus diisi.',
            'string' => ':attribute harus berupa teks.',
            'regex' => 'Format :attribute tidak valid.',
            'unique' => ':attribute sudah digunakan.',
            'min' => ':attribute minimal :min karakter.',
            'max' => ':attribute maksimal :max karakter.',
            'exists' => ':attribute yang dipilih tidak valid.',
        ];

        return $message;
    }

    private function attributeValidation()
    {
        $customAttributes = [
            'name' => 'Program Studi',
            'department' => 'Jurusan',
            'status' => 'Status',
        ];

        return $customAttributes;
    }
}
