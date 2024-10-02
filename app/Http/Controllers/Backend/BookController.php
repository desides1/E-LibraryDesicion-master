<?php

namespace App\Http\Controllers\Backend;

use App\Exports\BookCollectionExport;
use App\Models\Book;
use App\Models\CategoryBook;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Encryption\DecryptException;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Book::with(['user', 'category'])->where('user_id', Auth::id())->whereIn('status', ['Aktif', 'Tidak Aktif', 'Terealisasi'])->latest('publication_date')->get();

        return view('pages.book.index', [
            'title' => 'Koleksi Buku',
            'data' => $data
        ]);
    }

    public function exportExcel()
    {
        $data = Book::with(['user', 'category'])->where('user_id', Auth::id())->whereIn('status', ['Aktif', 'Terealisasi'])->latest('publication_date')->orderby('category_id', 'asc')->get();

        return Excel::download(new BookCollectionExport($data), 'book-collection-library_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = CategoryBook::all();

        return view('pages.book.create', [
            'title' => 'Tambah Koleksi Buku',
            'category' => $category
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
                'publisher_id' => 'nullable|exists:publishers,id',
                'title' => 'required|string|max:255',
                'publisher' => 'nullable|string|max:255',
                'isbn' => [
                    'required',
                    'string',
                    'regex:/^[a-zA-Z0-9]+(?:-[0-9a-zA-Z]+)*$/',
                    'min:9',
                    'max:20',
                    Rule::unique('publishers')->where(function ($query) {
                        return $query->where('isbn', request('isbn'));
                    }),
                    Rule::unique('books')->where(function ($query) {
                        return $query->where('isbn', request('isbn'));
                    }),
                ],
                'publication_date' => ['required', 'regex:/^\d{4}$/', 'gte:1900', 'lte:' . date('Y')],
                'category_id' => [
                    'required',
                    'exists:category_books,id',
                ],
                'author' => 'required|string|max:255',
                'price' => 'required|regex:/^[1-9]\d*(?:\.\d{1,2})?$/',
                'available_stock' => 'required|integer|min:0|regex:/^[1-9]\d*(?:\.\d{1,2})?$/',
                'abstract' => 'required|string',
                'type_book' => [
                    'required',
                    Rule::in(['E-Book', 'Cetak']),
                ],
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            ], $this->messageValidation(), $this->attributeValidation());

            $validation['user_id'] = Auth::id();
            $validation['status'] = 'Aktif';

            if ($request->file('image')) {
                $isbn = strtolower($validation['isbn']);
                $currentDate = now()->format('Ymd');

                $extension = $request->file('image')->getClientOriginalExtension();
                $imageName = "{$isbn}_{$currentDate}.{$extension}";

                $validation['image'] = $request
                    ->file('image')
                    ->storeAs('library-book', $imageName);
            }

            $data = Book::create($validation);

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'The book collection has been successfully registered.'
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to register the book collection.'
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

            $data = Book::findOrFail($id);

            return view('pages.book.show', [
                'title' => 'Detail Kelola Buku',
                'data' => $data,
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

            $data =  Book::findOrFail($id);
            $category = CategoryBook::all();

            return view('pages.book.update', [
                'title' => 'Edit Kelola Buku',
                'data' => $data,
                'category' => $category
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
                'publisher_id' => 'nullable|exists:publishers,id',
                'title' => 'required|string|max:255',
                'publisher' => 'nullable|string|max:255',
                'isbn' => [
                    'required',
                    'string',
                    'regex:/^[a-zA-Z0-9]+(?:-[0-9a-zA-Z]+)*$/',
                    'min:9',
                    'max:20',
                    Rule::unique('publishers')->where(function ($query) use ($request, $id) {
                        return $query->where('isbn', $request->isbn)
                            ->where('id', '!=', $id);
                    }),
                    Rule::unique('books')->where(function ($query) use ($request, $id) {
                        return $query->where('isbn', $request->isbn)
                            ->where('id', '!=', $id);
                    }),
                ],
                'publication_date' => ['required', 'regex:/^\d{4}$/', 'gte:1900', 'lte:' . date('Y')],
                'category_id' => [
                    'required',
                    'sometimes',
                    Rule::exists('category_books', 'id'),
                ],

                'author' => 'required|string|max:255',
                'price' => 'required|regex:/^[1-9]\d*(?:\.\d{1,2})?$/',
                'available_stock' => 'required|integer|min:0|regex:/^[1-9]\d*(?:\.\d{1,2})?$/',
                'abstract' => 'required|string',
                'type_book' => [
                    'required',
                    'sometimes',
                    Rule::in(['E-Book', 'Cetak']),
                ],
                'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            ], $this->messageValidation(), $this->attributeValidation());

            $validation['user_id'] = Auth::id();

            $book = Book::findOrFail($id);

            if ($request->file('image')) {
                if ($book->image && Storage::exists($book->image)) {
                    Storage::delete($book->image);
                }

                $isbn = strtolower($validation['isbn']);
                $currentDate = now()->format('Ymd');

                $extension = $request->file('image')->getClientOriginalExtension();
                $imageName = "{$isbn}_{$currentDate}.{$extension}";

                $validation['image'] = $request
                    ->file('image')
                    ->storeAs('library-book', $imageName);
            }

            $updated = $book->update($validation);

            if ($updated) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'The book collection has been successfully updated.'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update the book collection.'
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

    public function toggleStatus(Book $book)
    {
        $book->status = ($book->status === 'Aktif') ? 'Tidak Aktif' : 'Aktif';
        $book->save();

        return response()->json([
            'status' => $book->status
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
            'publication_date.regex' => 'Tahun terbit harus berupa tahun dengan format empat digit.',
            'publication_date.gte' => 'Tahun terbit tidak boleh kurang dari 1900.',
            'publication_date.lte' => 'Tahun terbit tidak boleh lebih dari tahun saat ini (' . date('Y') . ').',
        ];

        return $message;
    }

    private function attributeValidation()
    {
        $customAttributes = [
            'title' => 'Nama',
            'publisher' => 'Penerbit',
            'isbn' => 'ISBN',
            'publication_date' => 'Tahun Terbit',
            'category_id' => 'Klasifikasi Buku',
            'author' => 'Penulis',
            'price' => 'Harga',
            'available_stock' => 'Stok Buku',
            'abstract' => 'Abstrak',
        ];

        return $customAttributes;
    }
}
