<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index()
    {
        return view('pages.front.information.index', [
            'title' => 'Informasi',
            'title_head' => 'Informasi Perpustakaan',
        ]);
    }
}
