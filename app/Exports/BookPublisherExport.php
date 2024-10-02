<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;

class BookPublisherExport implements FromView
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
        return view('pages.book-publisher.export_excel', [
            'data' => $this->data
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $columnCount = count($this->data[0]);

                for ($column = 1; $column <= $columnCount; $column++) {
                    $sheet->getColumnDimensionByColumn($column)->setAutoSize(true);
                }
            },
        ];
    }
}
