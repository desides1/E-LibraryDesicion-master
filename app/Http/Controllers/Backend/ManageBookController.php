<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AlternativeBook;
use App\Models\Book;
use App\Models\BudgetBook;
use App\Models\PurchaseBook;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $budget = BudgetBook::latest('year')->first();

        $alternative = AlternativeBook::with(['book'])->get();

        $currentYear = Carbon::now()->year;
        $hasPurchaseData = PurchaseBook::whereYear('purchase_date', $currentYear)->exists();

        $moora = new MooraRecommendationController();
        $mergedData = $moora->alternativeAtribute($alternative);
        $decision = $moora->decisionMatrix($mergedData);
        $normalization = $moora->normalizationMatrix($decision);
        $optimization = $moora->optimizationValue($normalization);
        $rank = ($budget instanceof BudgetBook) ? $moora->rankRecommended($optimization, $budget->price) : $moora->rankRecommended($optimization, 0);
        // dd($rank);
        return view('pages.management-book.index', [
            'title' => 'Kelola Pengadaan Buku',
            'data' => $rank,
            'budget' => $budget,
            'submit' => $hasPurchaseData
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
        $userId = Auth::id();
        $purchaseDate = now();

        foreach ($request->data as $rowData) {
            PurchaseBook::create([
                'user_id' => $userId,
                'purchase_date' => $purchaseDate,
                'book_id' => $rowData['book_id'],
                'book_quantity' => $rowData['quantity'],
                'book_price' => $rowData['price'],
                'book_result' => str_replace('.', '', $rowData['result']),
                'status' => 'Proses',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data procurement saved successfully'
        ], 201);
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
}
