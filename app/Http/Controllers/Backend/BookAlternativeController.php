<?php

namespace App\Http\Controllers\Backend;

use App\Models\Unit;
use App\Models\Major;
use App\Models\Publisher;
use App\Models\BudgetBook;
use Illuminate\Http\Request;
use App\Models\AlternativeBook;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AlternativeUserExport;
use Illuminate\Contracts\Encryption\DecryptException;

class BookAlternativeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $major = Major::where('status', 'Aktif')->orderBy('department', 'asc')->orderBy('name', 'asc')->get();
        $unit = Unit::where('status', 'Aktif')->orderBy('name', 'asc')->get();
        $year = BudgetBook::latest('year')->get();

        $selectedMajor = $request->input('major', 'all');
        $selectedYear = $request->input('year', 'alltime');
        $selectedStatus = $request->input('status', 'allstatus');

        $datas = $this->filterAlternativeUser($selectedYear, $selectedStatus, $selectedMajor);
        $status = ['Dosen', 'Mahasiswa', 'Karyawan'];

        return view('pages.book-alternative.index', [
            'title' => 'Pengajuan Buku',
            'data' => $datas,
            'major' => $major,
            'unit' => $unit,
            'selectedMajor' => $selectedMajor,
            'selectedYear' => $selectedYear,
            'selectedStatus' => $selectedStatus,
            'year' => $year,
            'status' => $status
        ]);
    }

    public function exportExcel()
    {
        return Excel::download(new AlternativeUserExport(), 'alternative_request_book_' .  date('Y-m-d') . '.xlsx');
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

            $data = AlternativeBook::with(['publisher', 'borrowed'])->findOrFail($id);
            $publisher = Publisher::with(['user', 'category'])->where('id', $data->publisher_id)->first();

            return view('pages.book-alternative.show', [
                'title' => 'Detail Usulan Buku',
                'data' => $publisher,
                'item' => $data,
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

    private function filterAlternativeUser($selectedYear, $selectedStatus, $selectedMajor)
    {
        $alternativeQuery = AlternativeBook::with(['publisher.user', 'borrowed'])->latest('year');

        if ($selectedYear !== 'alltime') {
            $alternativeQuery->whereRaw('YEAR(year) = ?', [$selectedYear]);
        }

        if ($selectedStatus !== 'allstatus') {
            $alternativeQuery->whereHas('borrowed', function ($query) use ($selectedStatus) {
                $query->where('status', $selectedStatus);
            });
        }

        if ($selectedMajor !== 'all') {
            $alternativeQuery->whereHas('borrowed', function ($query) use ($selectedMajor) {
                $query->where('major', $selectedMajor);
            });
        }

        return $alternativeQuery->get();
    }
}
