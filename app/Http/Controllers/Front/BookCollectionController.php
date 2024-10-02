<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class BookCollectionController extends Controller
{
    public function index()
    {
        $data = Book::with('user')->filter(Request(['search-title', 'search-isbn', 'search-year']))->whereIn('status', ['Aktif', 'Terealisasi'])->latest('publication_date')->paginate(18)->withQueryString();

        return view('pages.front.book-collection.index', [
            'title' => 'Koleksi Buku',
            'title_head' => 'Koleksi Buku Perpustakaan',
            'data' => $data
        ]);
    }

    public function show($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);

            $data = Book::findOrFail($id);

            return view('pages.front.book-collection.show', [
                'title' => 'Koleksi Buku',
                'title_head' => 'Detail Koleksi Buku Perpustakaan',
                'item' => $data,
            ]);
        } catch (DecryptException $e) {
            abort(404);
        }
    }
}
