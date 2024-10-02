<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AlternativeBook;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AlternativeBookUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $book_library = Book::whereYear('publication_date', '>', 2000)->where('user_id', 37)->get();
        $book_publisher = Book::whereYear('publication_date', '>', 2000)->where('user_id', '!=', 37)->get();

        $data = AlternativeBook::with(['book'])->where('user_id', Auth::id())->get();

        return view('pages.alternative-user.index', [
            'title' => 'Pengajuan Buku',
            'book_library' => $book_library,
            'book_publisher' => $book_publisher,
            'data' => $data,
            // 'canSubmit' => $canSubmit
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

            $validation['user_id'] = Auth::id();
            $validation['year'] = date('Y');
            $validation['book_id'] = $request->book_id ?? 0;
            // $validation['publishers_id'] = $request->publishers_id ?? 0;

            // if ((!empty($validation['book_id']) && !empty($validation['publishers_id'])) ||
            //     (empty($validation['book_id']) && empty($validation['publishers_id']))
            // ) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Please provide either Book ID or Publishers ID, not both or none.'
            //     ], 423);
            // }

            // if (!empty($validation['book_id'])) {
            //     $validation['publishers_id'] = 0;
            // }

            // if (!empty($validation['publishers_id'])) {
            //     $validation['book_id'] = 0;
            // }

            $data = AlternativeBook::create($validation);

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'The book alternative user has been successfully registered.'
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to register the book alternative user.'
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
            'book_id' => 'Perpustakaan',
            'publisher_id' => 'Penerbit',
        ];

        return $customAttributes;
    }
}
