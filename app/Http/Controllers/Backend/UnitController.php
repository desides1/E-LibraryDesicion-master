<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Unit::orderBy('name', 'asc')->get();

        return view('pages.unit.index', [
            'title' => 'Unit Poliwangi',
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
        return view('pages.unit.create', [
            'title' => 'Tambah Unit Poliwangi',
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
                'name' => 'required|string|regex:/^[a-zA-Z0-9]+[a-zA-Z0-9 .,!-]*$/|max:255|unique:units',
            ], $this->messageValidation(), $this->attributeValidation());

            $validation['status'] = 'Aktif';

            $data = Unit::create($validation);

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'The unit has been successfully registered.'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to register the unit.'
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

            $data = Unit::findOrFail($id);

            return view('pages.unit.show', [
                'title' => 'Detail Unit Poliwangi',
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

            $data = Unit::findOrFail($id);

            return view('pages.unit.update', [
                'title' => 'Ubah Unit Poliwangi',
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
                'name' => 'required|string|regex:/^[a-zA-Z0-9]+[a-zA-Z0-9 .,!-]*$/|max:255|unique:units,name,' . $id,
            ], $this->messageValidation(), $this->attributeValidation());

            $data = Unit::findOrFail($id)->update($validation);

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'The unit has been successfully updated.'
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update the unit.'
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

    public function toggleStatus(Unit $unit)
    {
        $unit->status = ($unit->status === 'Aktif') ? 'Tidak Aktif' : 'Aktif';
        $unit->save();

        return response()->json([
            'status' => $unit->status
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
            'name' => 'Unit',
            'status' => 'Status',
        ];

        return $customAttributes;
    }
}
