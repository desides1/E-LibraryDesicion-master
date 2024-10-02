<?php

namespace App\Http\Controllers\Front;

use App\Models\Publisher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlternativeBook;
use App\Models\Book;
use App\Models\Borrowed;
use App\Models\CategoryBook;
use App\Models\Major;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BookRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $book_publisher = Publisher::with('user', 'category')->whereIn('status', ['Aktif'])->get();
        $data = Publisher::with(['user', 'category'])->filter(Request(['search-title', 'search-isbn', 'search-year']))->whereIn('status', ['Aktif'])->latest('publication_date')->paginate(18)->withQueryString();
        $category = CategoryBook::all();

        return view('pages.front.request_book.index', [
            'title' => 'Usulan Buku',
            'title_head' => 'Usulan Buku Baru',
            'book_publisher' => $book_publisher,
            'data' => $data,
            'major' => Major::where('status', 'Aktif')->get(),
            'unit' => Unit::where('status', 'Aktif')->get(),
            'category' => $category
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
            $request->validate([
                'name' => ['required', 'string', 'min:3', 'regex:/^[a-zA-Z]+[a-zA-Z\s.,]*$/', 'max:255'],
                'number_id' => ['required', 'string', 'min:3', 'regex:/^[1-9][0-9]*\.?[0-9]*$/', 'max:255'],
                'major' => ['nullable'],
                'unit' => ['nullable']
            ]);

            // Filter request data
            $requestData = $request->only(['name', 'number_id', 'status', 'major', 'unit']);

            // Ensure 'major' and 'unit' are handled based on 'status'
            if ($request->status === 'Dosen' || $request->status === 'Mahasiswa') {
                $requestData['major'] = $request->major;
            } else {
                $requestData['major'] = $request->unit;
            }

            if ($request->status === 'Karyawan') {
                $requestData['unit'] = $request->unit;
            } else {
                $requestData['unit'] = $request->major;
            }

            // Check if the entry exists
            $borrowed = Borrowed::where('number_id', $request->number_id)
                ->when($request->status === 'Dosen' || $request->status === 'Mahasiswa', function ($query) use ($request) {
                    $query->where('major', $request->major);
                })
                ->when($request->status === 'Karyawan', function ($query) use ($request) {
                    $query->where('major', $request->unit);
                })
                ->first();

            if ($borrowed) {
                // If entry already exists, perform update
                $borrowed->update([
                    'name' => $request->name,
                    'status' => $request->status,
                ]);
            } else {
                // If entry doesn't exist, create a new one
                // $borrowedData = [
                //     'name' => $request->name,
                //     'number_id' => $request->number_id,
                //     'status' => $request->status,
                // ];
                $borrowed = Borrowed::create($requestData);
            }

            if (!$borrowed) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to register the borrowed book user.'
                ], 500);
            }

            // Create Alternative Book
            $alternativeData = $request->validate([
                // 'borrowed_id' => 'required',
                // 'publisher_id' => 'required',
            ]);

            $alternativeData['borrowed_id'] = $borrowed->id;
            $alternativeData['publisher_id'] = $request->idBook;
            $alternativeData['year'] = date('Y-m-d');

            $alternative = AlternativeBook::create($alternativeData);

            if ($alternative) {
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
    public function show($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);

            $data = Publisher::findOrFail($id);
            $userAlternative = AlternativeBook::with(['borrowed', 'publisher'])->where('publisher_id', $id)->whereIn('id', function ($query) {
                $query->select(DB::raw('MIN(id)'))
                    ->from('alternative_books')
                    ->whereColumn('borrowed_id', 'alternative_books.borrowed_id')
                    ->groupBy('borrowed_id');
            })->paginate(15);

            return view('pages.front.request_book.show', [
                'title' => 'Usulan Buku',
                'title_head' => 'Detail Usulan Buku',
                'item' => $data,
                'userAlternative' => $userAlternative,
                'major' => Major::where('status', 'Aktif')->get(),
                'unit' => Unit::where('status', 'Aktif')->get(),
            ]);
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    public function searchData($encryptedId, Request $request)
    {
        try {
            if ($request->ajax()) {
                $id = decrypt($encryptedId);

                $userAlternative = AlternativeBook::with(['borrowed', 'publisher'])
                    ->where('publisher_id', $id);

                if ($request->filled('name')) {
                    $userAlternative->whereHas('borrowed', function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->input('name') . '%');
                    });
                }

                $data = $userAlternative
                    ->select('alternative_books.borrowed_id', DB::raw('COUNT(alternative_books.id) as result'))
                    ->groupBy('alternative_books.borrowed_id')
                    ->paginate(15);

                if ($data->isEmpty()) {
                    return response()->json(['error' => 'Data yang Anda cari tidak ditemukan.'], 404);
                }

                return view('pages.front.request_book.show_pagination', [
                    'userAlternative' => $data
                ])->render();
            }
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

    public function getBookDetail($id)
    {
        $data = Publisher::with(['user', 'category'])->findOrFail($id);
        $imagePath = Storage::exists($data->image) ? asset('storage/' . $data->image) : asset('components/img/cover-book.png') . '?v=' . time();

        return response()->json([
            'title' => $data->title,
            'isbn' => $data->isbn,
            'author' => $data->author,
            'publisher' => $data->user->name,
            'publication' => $data->publication_date,
            'category' => $data->category->name,
            'price' => 'Rp' . number_format($data->price, 0, ',', '.'),
            'stock' => $data->available_stock,
            'image' => $imagePath,
        ]);
    }

    public function storeSuggestion(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->validateRequest($request);

            $borrowed = $this->findOrCreateBorrowed($request);

            $bookData = $this->createBook($request);

            $alternative = $this->createAlternativeBook($borrowed, $bookData);

            if ($alternative) {
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'The book alternative user has been successfully registered.'
                ], 201);
            } else {
                DB::rollBack();
                $bookData->delete();

                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to register the book alternative user.'
                ], 500);
            }
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    private function validateRequest(Request $request)
    {
        Validator::extend('exists_in_major', function ($attribute, $value, $parameters, $validator) {
            return Major::where('status', 'Aktif')->where('name', $value)->exists();
        });

        return $request->validate([
            'name' => ['required', 'string', 'min:3', 'regex:/^[a-zA-Z]+[a-zA-Z\s.,]*$/', 'max:255'],
            'number_id' => ['required', 'string', 'min:3', 'regex:/^[1-9][0-9]*\.?[0-9]*$/', 'max:255'],
            'major' => ['nullable'],
            'unit' => ['nullable'],
            'title' => 'required|string|max:255',
            'publisher' => 'required|string|max:255|min:3',
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
            'publication_date' => ['required'],
            'category_id' => [
                'required',
                'exists:category_books,id',
            ],
            'author' => 'required|string|max:255',
            'price' => 'required|regex:/^[1-9]\d*(?:\.\d{1,2})?$/',
            'available_stock' => 'required|integer|min:0|regex:/^[1-9]\d*(?:\.\d{1,2})?$/',
            'type_book' => [
                'required',
                Rule::in(['E-Book', 'Cetak']),
            ],
        ], $this->messageValidation(), $this->attributeValidation());
    }

    private function findOrCreateBorrowed(Request $request)
    {
        $requestData = $request->only(['name', 'number_id', 'status', 'major', 'unit']);

        // Adjust 'major' and 'unit' based on 'status'
        if ($request->status === 'Dosen' || $request->status === 'Mahasiswa') {
            $requestData['major'] = $request->major;
            $requestData['unit'] = null; // Clear unit for non-Karyawan status
        } elseif ($request->status === 'Karyawan') {
            $requestData['major'] = $request->unit;
            $requestData['unit'] = $request->unit;
        } else {
            $requestData['major'] = null;
            $requestData['unit'] = null;
        }
        $requestData = $request->only(['name', 'number_id', 'status', 'major', 'unit']);

        // Check if the entry exists based on the status
        $borrowed = Borrowed::where('number_id', $request->number_id)
            ->when($request->status === 'Dosen' || $request->status === 'Mahasiswa', function ($query) use ($request) {
                $query->where('major', $request->major);
            })
            ->when($request->status === 'Karyawan', function ($query) use ($request) {
                $query->where('major', $request->unit);
            })
            ->first();
        if ($borrowed) {
            // If entry already exists, perform update
            $borrowed->update([
                'major' => $request->major,
                'name' => $request->name,
                'status' => $request->status,
            ]);
        } else {
            // If entry doesn't exist, create a new one
            $borrowed = Borrowed::create($requestData);
        }

        return $borrowed;
    }

    private function createBook(Request $request)
    {
        $isbn = strtolower($request->isbn);
        $currentDate = now()->format('Ymd');
        $adminUser = User::where('name', 'Admin')->first();

        $publisher = Publisher::create([
            'title' => $request->title,
            'isbn' => $request->isbn,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'publication_date' => $request->publication_date,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'available_stock' => $request->available_stock,
            'type_book' => $request->type_book,
            'user_id' => $adminUser->id,
            'status' => 'Usulan Pemustaka',
            'image' => 'library-book/' . $isbn . '_' . $currentDate . '.jpg',
            'abstract' => $request->title,
        ]);

        return $publisher;
    }

    public function getCreateRequest()
    {
        $category = CategoryBook::all();

        return view('pages.front.request_book.create_book', [
            'title' => 'Usulan Buku',
            'title_head' => 'Usulan Buku Baru',
            'major' => Major::where('status', 'Aktif')->get(),
            'unit' => Unit::where('status', 'Aktif')->get(),
            'category' => $category
        ]);
    }

    private function createAlternativeBook($borrowed, $bookData)
    {
        return AlternativeBook::create([
            'borrowed_id' => $borrowed->id,
            'publisher_id' => $bookData->id,
            'year' => date('Y-m-d'),
        ]);
    }

    private function messageValidation()
    {
        $message = [
            'required' => ':attribute harus diisi.',
            'string' => ':attribute harus berupa teks.',
            'name.regex' => ':attribute harus berupa karakter huruf.',
            'major.regex' => ':attribute harus berupa karakter huruf.',
            'number_id.regex' => ':attribute harus berupa angka atau titik.',
            'unique' => ':attribute sudah digunakan.',
            'min' => ':attribute minimal :min karakter.',
            'max' => ':attribute maksimal :max karakter.',
            'exists_in_major' => 'Nama :attribute yang anda masukkan tidak ada dan mohon tuliskan kembali dengan lengkap.',
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
            'name' => 'Nama',
            'major' => 'Program Studi atau Unit',
            'number_id' => 'Nomor Induk',
            'status' => 'Status',
            'title' => 'Nama',
            'publisher' => 'Penerbit',
            'isbn' => 'ISBN',
            'publication_date' => 'Tahun Terbit',
            'category_id' => 'Klasifikasi Buku',
            'author' => 'Penulis',
            'price' => 'Harga',
            'available_stock' => 'Stok Buku',
            // 'abstract' => 'Abstrak',
        ];

        return $customAttributes;
    }
}
