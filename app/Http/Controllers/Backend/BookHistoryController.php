<?php

namespace App\Http\Controllers\Backend;

use App\Models\BudgetBook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\PurchaseBook;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;

class BookHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = BudgetBook::latest('year')->get();

        return view('pages.book-history.index', [
            'title' => 'Riwayat Pengadaan Buku',
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
    public function show($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);

            $data = BudgetBook::findOrFail($id);

            $books = PurchaseBook::with('book')
                ->whereYear('purchase_date', $data->year)
                ->get();

            $payment_book = $books->sum('book_result');
            $ppn = $payment_book * ($data->ppn / 100);
            $ppnPayment = $payment_book + $ppn;

            return view('pages.book-history.show', [
                'title' => 'Riwayat Pengadaan Buku Tahun ' . $data->year,
                'data' => $books,
                'budget' => $data,
                'payment_book' => $payment_book,
                'ppn_payment' => $ppnPayment
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

    public function updateStatusProcess($id)
    {
        $purchaseBook = PurchaseBook::findOrFail($id);

        if ($purchaseBook->status === 'Proses') {
            $purchaseBook->update(['status' => 'Terealisasi']);

            if ($purchaseBook->book->user_id == 37) {
                $book = $purchaseBook->book;
                $book->update(['available_stock' => $book->available_stock + $purchaseBook->book_quantity]);
            } else {
                $book = $purchaseBook->book;

                Book::create([
                    'user_id' => $purchaseBook->user_id,
                    'category_id' => $book->category_id,
                    'title' => $book->title,
                    'isbn' => $book->isbn,
                    'publication_date' => $book->publication_date,
                    'author' => $book->author,
                    'price' => $book->price,
                    'available_stock' => $purchaseBook->book_quantity,
                    'abstract' => $book->abstract,
                    'publisher' => $book->user->name
                ]);
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
            $purchaseBook->update(['status' => 'Proses']);

            if ($purchaseBook->book->user_id == 37) {
                $book = $purchaseBook->book;
                $book->update(['available_stock' => $book->available_stock - $purchaseBook->book_quantity]);
            } else {
                $id_selection = Book::where('title', $purchaseBook->book->title)
                    ->where('available_stock', $purchaseBook->book_quantity)
                    ->value('id');

                if ($id_selection) {
                    $book = Book::findOrFail($id_selection);
                    $book->delete();
                }
            }

            return response()->json(['message' => 'Status berhasil diperbarui'], 200);
        } else {
            return response()->json(['error' => 'Status tidak dapat diperbarui'], 400);
        }
    }
}
