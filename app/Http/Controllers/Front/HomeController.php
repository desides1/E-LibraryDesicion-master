<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $book_recent = Book::where('status', 'Terealisasi')->latest()->first() ?: Book::where('status', 'Aktif')->latest('publication_date')->first();
        $publisher_count = User::role('Penerbit')->withCount('roles')->count();
        $book_count = Book::whereIn('status', ['Aktif', 'Terealisasi'])->count();
        $book_realizations = Book::where('status', 'Terealisasi')->count();

        $carouselItems = [
            [
                'image' => 'components/img/library_primary.jpg',
                'alt' => 'Image',
                'title' => 'SiPekan',
            ],
            [
                'image' => 'components/img/library.jpg',
                'alt' => 'Image',
                'title' => 'Bersama SiPekan',
            ]
        ];

        return view('pages.front.home.index', [
            'title' => 'Beranda',
            'book_recent' => $book_recent,
            'publisher' => $publisher_count,
            'book_count' => $book_count,
            'book_realizations' => $book_realizations,
            'carouselItems' => $carouselItems
        ]);
    }
}
