<?php

namespace App\Http\Controllers\Backend;

use App\Models\Book;
use App\Models\CategoryBook;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Encryption\DecryptException;

class CategoryBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = CategoryBook::orderBy('code', 'asc')->get();

        return view('pages.category-book.index', [
            'title' => 'Klasifikasi Buku',
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
        try {
            $validation = $request->validate([
                'code' => [
                    'required',
                    'unique:category_books,code',
                    'regex:/^[A-Za-z0-9][A-Za-z0-9\s]*[A-Za-z0-9]$/',
                ],
                'name' => [
                    'required',
                    'regex:/^[A-Za-z][A-Za-z\s]*[A-Za-z]$/',
                ],
            ], $this->messageValidation(), $this->attributeValidation());

            $data = CategoryBook::create($validation);

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'The book classification has been successfully registered.'
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to register the book classification.'
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

            $data = Book::with(['category', 'user'])->where('category_id', $id)->where('user_id', auth()->user()->id)->get();

            return view('pages.category-book.show', [
                'title' => 'Detail Klasifikasi Buku',
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

            $data = CategoryBook::findOrFail($id);

            return view('pages.category-book.update', [
                'title' => 'Ubah Klasifikasi Buku',
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
                'code' => [
                    'required',
                    Rule::unique('category_books', 'code')->ignore($id),
                    'regex:/^[A-Za-z0-9][A-Za-z0-9\s]*[A-Za-z0-9]$/',
                ],
                'name' => [
                    'required',
                    'regex:/^[A-Za-z][A-Za-z\s]*[A-Za-z]$/',
                ],
            ], $this->messageValidation(), $this->attributeValidation());

            $data = CategoryBook::findOrFail($id);
            $data->update($validation);

            return response()->json([
                'status' => 'success',
                'message' => 'The book classification has been successfully updated.'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update the book classification.'
            ], 500);
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
            'code' => 'Kode Klasifikasi Buku',
            'name' => 'Nama Klasifikasi Buku',
        ];

        return $customAttributes;
    }
}
