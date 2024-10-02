<?php

namespace App\Exports;

use App\Models\Publisher;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;

class PublisherSheetCollectionExport implements FromView, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $user;
    protected $data;

    public function __construct($user, $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    public function view(): View
    {
        return view('pages.publisher.export_excel', [
            'data' => $this->data
        ]);
    }

    public function title(): string
    {
        return $this->user->name;
    }
}
