<?php

namespace App\Exports;

use App\Models\AlternativeBook;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class AlternativeSheetUserExport implements FromView, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $major;
    protected $data;

    public function __construct($major, $data)
    {
        $this->major = $major;
        $this->data = $data;
    }

    public function view(): View
    {
        return view('pages.book-alternative.export_excel', [
            'data' => $this->data
        ]);
    }

    public function title(): string
    {
        return $this->major->major;
    }
}
