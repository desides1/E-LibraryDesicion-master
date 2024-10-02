<?php

namespace App\Http\Controllers\Backend;

use App\Exports\AlternativeExport;
use App\Http\Controllers\Backend\Recommendation\CalculationController;
use App\Models\Major;
use App\Models\Criteria;
use App\Models\BudgetBook;
use App\Models\SubCriteria;
use App\Models\PurchaseBook;
use Illuminate\Http\Request;
use App\Models\AlternativeBook;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\Backend\Recommendation\SawController;
use App\Http\Controllers\Backend\Recommendation\MooraController;
use App\Http\Controllers\Backend\Recommendation\TopsisController;
use App\Http\Controllers\Backend\Recommendation\WpmController;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class BudgetBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = BudgetBook::orderBy('year', 'desc')->get();

        return view('pages.budget-book.index', [
            'title' => 'Usulan Pengadaan Buku',
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
        return view('pages.budget-book.create', [
            'title' => 'Tambah Usulan Pengadaan Buku',
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
                'price' => 'required|regex:/^[1-9]\d*(?:\.\d{1,2})?$/',
                'ppn' => 'required|regex:/^[1-9]\d*(?:\.\d{1,2})?$/',
                'year' => 'required|date_format:Y|unique:budget_books,year',
            ], $this->messageValidation(), $this->attributeValidation());

            $data = BudgetBook::create($validation);

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'The book budget has been successfully registered.'
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to register the book budget.'
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
    public function show($encryptedId, Request $request)
    {
        $criteria = Criteria::where('status', 'Aktif')->whereNotIn('name', ['Program Studi'])->orderBy('code', 'asc')->get();
        $id = decrypt($encryptedId);
        $budget = BudgetBook::findOrFail($id);
        $major = Major::where('status', 'Aktif')->orderBy('department', 'asc')->orderBy('name', 'asc')->get();

        $selectedMajor = $request->input('major', 'all');
        $selectedRecom = $request->input('recom', 'moora');
        $selectedCalculate = $request->input('calavg', []);

        if ($selectedMajor !== 'all') {
            $majorName = Major::where('name', '=', $selectedMajor)->first();
            $subCriteria = SubCriteria::where('name_sub', $selectedMajor)->first();
            $budgetProdi = $budget->price * $subCriteria->value;
            $paymentBook = PurchaseBook::where('major_id', $majorName->id)->sum('book_result');
            $ppn = $paymentBook * ($budget->ppn / 100);
            $ppnPayment = $paymentBook + $ppn;
        }

        $alternative = $this->filterAlternativesByMajor(
            $budget,
            $selectedMajor
        );

        if ($selectedRecom === 'moora') {
            $recommender = new MooraController();
        } elseif ($selectedRecom === 'topsis') {
            $recommender = new TopsisController();
        } elseif ($selectedRecom === 'saw') {
            $recommender = new SawController();
        } elseif ($selectedRecom === 'wpm') {
            $recommender = new WpmController();
        } elseif ($selectedRecom === 'calculate') {
            $recommender = new CalculationController();
        } else {
            $recommender = new MooraController();
        }

        $atributeAlternative = $recommender->getAlternativeBook($alternative);
        $decisionAlternative = $recommender->getDecisionMatrixBook($atributeAlternative);
        $normalizationAlternative = $recommender->getNormalizationMatrix($decisionAlternative, $criteria);
        $optimizationAlternative = $recommender->getOptimizationAttribute($normalizationAlternative, $criteria);
        $rankAlternative = $recommender->getRangkingValue($optimizationAlternative, $criteria, $selectedCalculate);

        // dd($rankAlternative);

        return view('pages.budget-book.show', [
            'title' => 'Rekomendasi Pengadaan Buku Anggaran Rp' . number_format($selectedMajor === 'all' ? $budget->price : $budgetProdi, 0, ',', '.'),
            'criteria' => $criteria,
            'budgetProdi' => $selectedMajor === 'all' ? $budget->price : $budgetProdi,
            'majorName' => $selectedMajor === 'all' ? '' : $majorName,
            'paymentBook' => $selectedMajor === 'all' ? 0 : $paymentBook,
            'ppnPayment' => $selectedMajor === 'all' ? 0 : $ppnPayment,
            'budget' => $budget,
            'major' => $major,
            'calulateSelected' => $selectedCalculate,
            'selectedMajor' => $selectedMajor,
            'selectedRecom' => $selectedRecom,
            'alternative' => $atributeAlternative,
            'decision' => $decisionAlternative,
            'normalization' => $normalizationAlternative,
            'optimization' => $optimizationAlternative,
            'rank' => $rankAlternative
        ]);
    }

    public function history($encryptedId, Request $request)
    {
        try {
            $id = decrypt($encryptedId);

            $data = BudgetBook::findOrFail($id);

            $major = Major::where('status', 'Aktif')->orderBy('department', 'asc')->orderBy('name', 'asc')->get();
            $selectedMajor = $request->input('major', 'all');

            if ($selectedMajor !== 'all') {
                $majorName = Major::where('name', '=', $selectedMajor)->first();
                $subCriteria = SubCriteria::where('criteria_id', 6)->where('name_sub', $selectedMajor)->first();
                $budgetProdi = $data->price * $subCriteria->value;
            }

            $books = $this->filterHistoryMajor($data, $selectedMajor);

            $payment_book = $books->sum('book_result');
            $ppn = $payment_book * ($data->ppn / 100);
            $ppnPayment = $payment_book + $ppn;

            return view('pages.book-history.show', [
                'title' => 'Riwayat Pengadaan Buku Tahun ' . $data->year,
                'data' => $books,
                'budget' => $data,
                'payment_book' => $payment_book,
                'ppn_payment' => $ppnPayment,
                'major' => $major,
                'selectedMajor' => $selectedMajor,
                'budgetProdi' => $selectedMajor === 'all' ? $data->price : $budgetProdi,
                'majorName' => $selectedMajor === 'all' ? '' : $majorName,
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

            $data = BudgetBook::findOrFail($id);

            return view('pages.budget-book.update', [
                'title' => 'Edit Usulan Pengadaan Buku',
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
                'price' => 'required|regex:/^[1-9]\d*(?:\.\d{1,2})?$/',
                'ppn' => 'required|regex:/^[1-9]\d*(?:\.\d{1,2})?$/',
                'year' => [
                    'required',
                    'date_format:Y',
                    Rule::unique('budget_books', 'year')->ignore($id)
                ],
            ], $this->messageValidation(), $this->attributeValidation());

            $data = BudgetBook::findOrFail($id);

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Book budget not found.'
                ], 404);
            }

            $data->update($validation);

            return response()->json([
                'status' => 'success',
                'message' => 'The book budget has been successfully updated.'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update the book budget.'
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
        $data = PurchaseBook::findOrFail($id);

        $book = Book::where('isbn', $data->publisher->isbn)->first();

        if ($data->status == 'Terealisasi') {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete the purchase book, because status is realization.'
            ], 422);
        } else if ($book) {
            $book->delete();
            $query = $data->delete();
        } else {
            $query = $data->delete();
        }

        if ($query) {
            return response()->json([
                'status' => 'success',
                'message' => 'The purchase book has been successfully deleted.'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete the purchase book.'
            ], 500);
        }
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
            'price' => 'Anggaran Buku',
            'year' => 'Tahun Anggaran Buku',
        ];

        return $customAttributes;
    }

    private function filterAlternativesByMajor($budget, $selectedMajor)
    {
        $alternativeQuery = AlternativeBook::with(['publisher'])
            ->whereRaw('YEAR(year) = ?', [$budget->year])
            ->orderBy('publisher_id', 'asc');

        if ($selectedMajor === 'all') {
            $existingMajors = Major::pluck('name')->toArray();
            $alternativeQuery->whereHas('borrowed', function ($query) use ($existingMajors) {
                $query->whereIn('major', $existingMajors);
            });
        }

        if ($selectedMajor !== 'all') {
            $alternativeQuery->whereHas('borrowed', function ($query) use ($selectedMajor) {
                $query->where('major', $selectedMajor);
            });
        }

        $alternativeQuery->select('publisher_id', DB::raw('COUNT(*) as request_book_count'));
        $alternativeQuery->groupBy('publisher_id');

        return $alternativeQuery->orderBy('publisher_id', 'asc')->get();
    }

    private function filterHistoryMajor($data, $selectedMajor)
    {
        $historyQuery = PurchaseBook::with('publisher')
            ->whereYear('purchase_date', $data->year);

        if ($selectedMajor === 'all') {
            $existingMajors = Major::pluck('name')->toArray();
            $historyQuery->whereHas('major', function ($query) use ($existingMajors) {
                $query->whereIn('name', $existingMajors);
            });
        }

        if ($selectedMajor !== 'all') {
            $historyQuery->whereHas('major', function ($query) use ($selectedMajor) {
                $query->where('name', $selectedMajor);
            });
        }

        return $historyQuery->get();
    }

    public function payment(Request $request)
    {
        $userId = Auth::id();
        $purchaseDate = now();

        foreach ($request->data as $rowData) {
            $purchase = PurchaseBook::where('publisher_id', $rowData['publisher_id'])->first();

            if ($purchase !== null) {
                $purchase->update([
                    'book_quantity' => $purchase->book_quantity + $rowData['quantity'],
                    'book_result' => $purchase->book_result + $rowData['result'],
                ]);
            } else {
                PurchaseBook::create([
                    'user_id' => $userId,
                    'purchase_date' => $purchaseDate,
                    'publisher_id' => $rowData['publisher_id'],
                    'major_id' => $rowData['major_id'],
                    'book_quantity' => $rowData['quantity'],
                    'book_price' => $rowData['price'],
                    'book_result' => str_replace('.', '', $rowData['result']),
                    'status' => 'Proses',
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data procurement saved successfully'
        ], 201);
    }

    public function updateStatusProcess($id)
    {
        $purchaseBook = PurchaseBook::findOrFail($id);

        if ($purchaseBook->status === 'Proses') {
            $bookFound = Book::where('isbn', $purchaseBook->publisher->isbn)->first();

            if ($bookFound !== null) {
                $book = $purchaseBook->publisher;

                if ($book->available_stock - $purchaseBook->book_quantity > 0) {
                    $book->update(['available_stock' => $book->available_stock - $purchaseBook->book_quantity]);
                    $purchaseBook->update(['status' => 'Terealisasi']);
                    $bookFound->update(['available_stock' => $book->available_stock + $purchaseBook->book_quantity]);
                } else {
                    return response()->json(['error' => 'Status tidak dapat diperbarui, stock buku tidak mencukupi'], 422);
                }
            } else {
                $book = $purchaseBook->publisher;

                $image = $book->image;
                $new_image = str_replace("publisher-book/", "library-book/", $image);

                if ($book->available_stock - $purchaseBook->book_quantity > 0) {
                    Book::create([
                        'user_id' => Auth::id(),
                        'publisher_id' => $book->user_id,
                        'category_id' => $book->category_id,
                        'title' => $book->title,
                        'publisher' => $book->user->name,
                        'isbn' => $book->isbn,
                        'image' => $new_image,
                        'publication_date' => $book->publication_date,
                        'type_boook' => $book->type_book,
                        'author' => $book->author,
                        'price' => $book->price,
                        'available_stock' => $purchaseBook->book_quantity,
                        'abstract' => $book->abstract,
                        'status' => 'Terealisasi',
                    ]);
                    $purchaseBook->update(['status' => 'Terealisasi']);

                    $book->update(['available_stock' => $book->available_stock - $purchaseBook->book_quantity]);
                } else {
                    return response()->json(['error' => 'Status tidak dapat diperbarui, stock buku tidak mencukupi'], 422);
                }
            }

            return response()->json(['message' => 'Status berhasil diperbarui'], 200);
        } else {
            return response()->json(['error' => 'Status tidak dapat diperbarui'], 400);
        }
    }

    public function updateStatusRealization($id)
    {
        $purchaseBook = PurchaseBook::findOrFail($id);

        if ($purchaseBook->status === 'Terealisasi') {

            $book = $purchaseBook->publisher;

            $id_selection = Book::where('isbn', $book->isbn)
                ->where('title', $book->title)
                ->value('id');

            if ($id_selection) {
                $bookFound = Book::findOrFail($id_selection);
                $bookFound->delete();

                $purchaseBook->update(['status' => 'Proses']);
                $book->update(['available_stock' => $book->available_stock + $purchaseBook->book_quantity]);
            }

            return response()->json(['message' => 'Status berhasil diperbarui'], 200);
        } else {
            return response()->json(['error' => 'Status tidak dapat diperbarui'], 400);
        }
    }

    public function print($encryptedId, Request $request)
    {
        $criteria = Criteria::where('status', 'Aktif')->whereNotIn('name', ['Program Studi'])->orderBy('code', 'asc')->get();
        $id = decrypt($encryptedId);
        $budget = BudgetBook::findOrFail($id);
        $major = Major::where('status', 'Aktif')->orderBy('department', 'asc')->orderBy('name', 'asc')->get();

        $selectedMajor = $request->input('major', 'all');
        $selectedRecom = $request->input('recom', 'moora');
        $selectedCalculate = $request->input('calavg', []);

        if ($selectedMajor !== 'all') {
            $majorName = Major::where('name', '=', $selectedMajor)->first();
            $subCriteria = SubCriteria::where('name_sub', $selectedMajor)->first();
            $budgetProdi = $budget->price * $subCriteria->value;
        }

        $alternative = $this->filterAlternativesByMajor(
            $budget,
            $selectedMajor
        );

        if ($selectedRecom === 'moora') {
            $recommender = new MooraController();
        } elseif ($selectedRecom === 'topsis') {
            $recommender = new TopsisController();
        } elseif ($selectedRecom === 'saw') {
            $recommender = new SawController();
        } elseif ($selectedRecom === 'wpm') {
            $recommender = new WpmController();
        } elseif ($selectedRecom === 'calculate') {
            $recommender = new CalculationController();
        } else {
            $recommender = new MooraController();
        }

        $atributeAlternative = $recommender->getAlternativeBook($alternative);
        $decisionAlternative = $recommender->getDecisionMatrixBook($atributeAlternative);
        $normalizationAlternative = $recommender->getNormalizationMatrix($decisionAlternative, $criteria);
        $optimizationAlternative = $recommender->getOptimizationAttribute($normalizationAlternative, $criteria);
        $rankAlternative = $recommender->getRangkingValue($optimizationAlternative, $criteria, $selectedCalculate);

        return view('pages.budget-book.recommendation.print', [
            'title' => 'Rekomendasi Pengadaan Buku Anggaran Rp' . number_format($selectedMajor === 'all' ? $budget->price : $budgetProdi, 0, ',', '.'),
            'criteria' => $criteria,
            'budgetProdi' => $selectedMajor === 'all' ? $budget->pricec : $budgetProdi,
            'majorName' => $selectedMajor === 'all' ? '' : $majorName,
            'budget' => $budget,
            'major' => $major,
            'calulateSelected' => $selectedCalculate,
            'selectedMajor' => $selectedMajor,
            'selectedRecom' => $selectedRecom,
            'alternative' => $atributeAlternative,
            'decision' => $decisionAlternative,
            'normalization' => $normalizationAlternative,
            'optimization' => $optimizationAlternative,
            'rank' => $rankAlternative
        ]);
    }

    public function exportExcel($encryptedId, Request $request)
    {
        // $criteria = Criteria::where('status', 'Aktif')->whereNotIn('name', ['Program Studi'])->orderBy('code', 'asc')->get();
        $id = decrypt($encryptedId);
        $budget = BudgetBook::findOrFail($id);
        $major = Major::where('status', 'Aktif')->orderBy('department', 'asc')->orderBy('name', 'asc')->get();

        $selectedRecom = $request->input('recom', 'moora');
        $selectedMajor = $request->input('major', 'all');

        if ($selectedMajor !== 'all') {
            $majorName = Major::where('name', '=', $selectedMajor)->first();
            $subCriteria = SubCriteria::where('name_sub', $selectedMajor)->first();
            $budgetProdi = $budget->price * $subCriteria->value;
        }

        $alternative = $this->filterAlternativesByMajor(
            $budget,
            $selectedMajor
        );

        if ($selectedRecom === 'moora') {
            $recommender = new MooraController();
        } elseif ($selectedRecom === 'topsis') {
            $recommender = new TopsisController();
        } elseif ($selectedRecom === 'saw') {
            $recommender = new SawController();
        } elseif ($selectedRecom === 'wpm') {
            $recommender = new WpmController();
        } else {
            $recommender = new MooraController();
        }

        $atributeAlternative = $recommender->getAlternativeBook($alternative);
        $decisionAlternative = $recommender->getDecisionMatrixBook($atributeAlternative);

        return Excel::download(new AlternativeExport($decisionAlternative), 'rekomendasi-' . $selectedMajor . '.xlsx');
    }

    public function getBookDetail($id)
    {
        $data = Publisher::with(['user', 'category'])->findOrFail($id);

        return response()->json([
            'title' => $data->title,
            'isbn' => $data->isbn,
            'author' => $data->author,
            'publisher' => $data->publisher->user->name,
            'publication' => $data->publication_date,
            'category' => $data->category->name,
            'price' => 'Rp' . number_format($data->price, 0, ',', '.'),
            'stock' => $data->available_stock
        ]);
    }
}
