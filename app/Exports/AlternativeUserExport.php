<?php

namespace App\Exports;

use App\Models\AlternativeBook;
use App\Exports\AlternativeSheetUserExport;
use App\Models\Borrowed;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AlternativeUserExport implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function sheets(): array
    {
        $borrowed = Borrowed::all();
        $borrowedMajor = Borrowed::distinct('major')->get();
        $data = AlternativeBook::with(['borrowed', 'publisher.user'])
            ->whereIn('borrowed_id', $borrowed->pluck('id')->toArray())
            ->latest('year')->orderBy('year', 'asc')->get();

        $sheets = [];
        $majorData = [];

        foreach ($data as $publisher) {
            $major = $publisher->borrowed->major;
            if (!isset($majorData[$major])) {
                $majorData[$major] = [];
            }
            $majorData[$major][] = $publisher;
        }

        foreach ($borrowedMajor as $major) {
            if (isset($majorData[$major->major])) {
                $sheets[] = new AlternativeSheetUserExport($major, $majorData[$major->major]);
                unset($majorData[$major->major]);
            }
        }

        return $sheets;
    }
}
