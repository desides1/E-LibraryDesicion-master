<?php

namespace App\Exports;

use App\Models\AlternativeBook;
use App\Models\Borrowed;
use App\Models\Criteria;
use App\Models\Major;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AlternativeExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $criteria = Criteria::where('status', 'Aktif')->whereNotIn('name', ['Program Studi']);

        // dd($this->data);
        return view('pages.budget-book.recommendation.decision')->with([
            'decision' => $this->data,
            'criteria' => $criteria
        ]);
    }
}
