<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PublisherCollectionExport;
use App\Models\User;
use App\Models\Publisher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Contracts\Encryption\DecryptException;
use Maatwebsite\Excel\Facades\Excel;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedPublisher = $request->input('publisher', 'all');

        $dataPublisher = $this->filterAlternativePublisher($selectedPublisher);
        $user = User::whereHas('roles', function ($query) {
            $query->where('name', 'Penerbit');
        })->whereIn('id', function ($query) {
            $query->select('user_id')
                ->from('publishers')
                ->where('status', 'aktif');
        })->get();

        return view('pages.publisher.index', [
            'title' => 'Penerbit',
            'data' => $dataPublisher,
            'user' => $user,
            'selectedPublisher' => $selectedPublisher
        ]);
    }

    public function exportExcel()
    {
        return Excel::download(new PublisherCollectionExport, 'publisher-collection_' . date('Y-m-d') . '.xlsx');
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

            $data = Publisher::with('category')->where('user_id', $id)->where('status', 'Aktif')->get();

            return view('pages.publisher.show', [
                'title' => 'Detail Data Buku Penerbit',
                'data' => $data
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

    public function detailPublisher($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);

            $data = Publisher::with('category', 'user')->findOrFail($id);

            return view('pages.publisher.show-publisher', [
                'title' => 'Detail Koleksi Buku',
                'data' => $data,
            ]);
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    private function filterAlternativePublisher($selectedPublisher)
    {
        $alternativeQuery = Publisher::with(['category', 'user'])->where('status', 'Aktif')->latest('publication_date');

        if ($selectedPublisher !== 'all') {
            $alternativeQuery->whereHas('user', function ($query) use ($selectedPublisher) {
                $query->where('name', $selectedPublisher);
            });
        }

        return $alternativeQuery->get();
    }
}
