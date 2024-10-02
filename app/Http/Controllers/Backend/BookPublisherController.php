<?php

namespace App\Http\Controllers\Backend;

use App\Exports\BookPublisherExport;
use App\Models\Publisher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\PublisherCollectionDataImport;
use App\Models\CategoryBook;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class BookPublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Publisher::with(['category', 'user'])->where('user_id', auth()->user()->id)->whereIn('status', ['Aktif', 'Tidak Aktif'])->latest('publication_date')->orderby('category_id', 'asc')->get();

        return view('pages.book-publisher.index', [
            'title' => 'Koleksi Buku',
            'data' => $data
        ]);
    }

    public function exportExcel()
    {
        $data = Publisher::with(['category', 'user'])->where('user_id', auth()->user()->id)->where('status', 'Aktif')->latest('publication_date')->get();

        return Excel::download(new BookPublisherExport($data), 'book-publisher-' . User::find(auth()->user()->id)->name . '_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = CategoryBook::all();

        return view('pages.book-publisher.create', [
            'title' => 'Tambah Koleksi Buku',
            'category' => $category
        ]);
    }

    public function importExcel()
    {
        return view('pages.book-publisher.import_excel', [
            'title' => 'Import Koleksi Buku'
        ]);
    }

    public function storeImportExcel(Request $request)
    {
        try {
            $this->validate($request, [
                'importexcel' => 'required|mimes:xlsx'
            ], $this->messageValidation(), $this->attributeValidation());

            $file = $request->file('importexcel');

            $import = new PublisherCollectionDataImport;
            Excel::import($import, $file);

            $successCount = $import->getSuccessCount();
            $failCount = $import->getFailCount();

            if ($failCount === 0) {
                return response()->json([
                    'message' => 'Import successful',
                    'success_count' => $successCount,
                    'fail_count' => $failCount
                ], 200);
            } elseif ($successCount === 0) {
                return response()->json([
                    'error' => 'All records failed to import',
                    'success_count' => $successCount,
                    'fail_count' => $failCount
                ], 417);
            } else {
                return response()->json([
                    'error' => 'Some records failed to import',
                    'success_count' => $successCount,
                    'fail_count' => $failCount
                ], 419);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred during import: ' . $e->getMessage()], 500);
        }
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
                'title' => 'required|string',
                'publisher' => 'required|string|max:255',
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
            // $validation['publisher'] = Auth::user()->name;
            $validation['status'] = 'Aktif';

            if ($request->file('image')) {
                $isbn = strtolower($validation['isbn']);
                $currentDate = now()->format('Ymd');

                $extension = $request->file('image')->getClientOriginalExtension();
                $imageName = "{$isbn}_{$currentDate}.{$extension}";

                $validation['image'] = $request
                    ->file('image')
                    ->storeAs('publisher-book', $imageName);
            }

            $data = Publisher::create($validation);

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

            $data = Publisher::with('category')->findOrFail($id);

            return view('pages.book-publisher.show', [
                'title' => 'Detail Koleksi Buku',
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

            $data = Publisher::findOrFail($id);
            $category = CategoryBook::all();

            return view('pages.book-publisher.update', [
                'title' => 'Ubah Koleksi Buku',
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
                'publisher' => 'required|string|max:255',
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
            // $validation['publisher'] = Auth::user()->name;

            $book = Publisher::findOrFail($id);

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
                    ->storeAs('publisher-book', $imageName);
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

    public function toggleStatus(Publisher $publisher)
    {
        $publisher->status = ($publisher->status === 'Aktif') ? 'Tidak Aktif' : 'Aktif';
        $publisher->save();

        return response()->json([
            'status' => $publisher->status
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
            'importexcel.mimes' => 'File harus berekstensi .xlsx.',
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
            'importexcel' => 'File Excel',
        ];

        return $customAttributes;
    }
}
