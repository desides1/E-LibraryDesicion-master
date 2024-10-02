<?php

namespace App\Http\Controllers\Backend;

use App\Models\Criteria;
use App\Models\BudgetBook;
use Illuminate\Http\Request;
use App\Models\AlternativeBook;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Backend\MooraRecommendationController;

class RecommendationBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $criteria = Criteria::all();
        $budget = BudgetBook::latest('year')->first();

        $alternative = AlternativeBook::with(['book'])->get();

        $moora = new MooraRecommendationController();
        $mergedData = $moora->alternativeAtribute($alternative);
        $decision = $moora->decisionMatrix($mergedData);
        $normalization = $moora->normalizationMatrix($decision);
        $optimization = $moora->optimizationValue($normalization);
        $rank = ($budget instanceof BudgetBook) ? $moora->rangkingValue($optimization, $budget->price) : $moora->rangkingValue($optimization, 0);

        return view('pages.book-recommendation.index', [
            'title' => 'Rekomendasi Pengadaan Buku ' . ($budget->year ?? now()->year) . ' Anggaran Rp' . number_format(($budget->price ?? 0), 0, ',', '.'),
            'criteria' => $criteria,
            'alternative' => $mergedData,
            'decision' => $decision,
            'normalization' => $normalization,
            'optimization' => $optimization,
            'rank' => $rank,
            'budget' => $budget
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
    public function show($id)
    {
        //
    }

    public function print()
    {
        $budget = BudgetBook::latest('year')->first();

        $alternative = AlternativeBook::with(['book'])->get();

        $moora = new MooraRecommendationController();
        $mergedData = $moora->alternativeAtribute($alternative);
        $decision = $moora->decisionMatrix($mergedData);
        $normalization = $moora->normalizationMatrix($decision);
        $optimization = $moora->optimizationValue($normalization);
        $rank =  $moora->rangkingValue($optimization, $budget->price);

        return view('pages.book-recommendation.print', [
            'title' => 'Rekomendasi Pengadaan Buku',
            'alternative' => $mergedData,
            'decision' => $decision,
            'normalization' => $normalization,
            'optimization' => $optimization,
            'rank' => $rank,
            'budget' => $budget
        ]);
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
