<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\BookPublisherController;

Route::group(['middleware' => ['auth', 'role:Penerbit']], function () {
    Route::resource('/book-list', BookPublisherController::class);
    // Route::get('/book-list/import-excel', [BookPublisherController::class, 'importExcel']);
    Route::post('/book-list/{publisher}/toggle-status', [BookPublisherController::class, 'toggleStatus']);
});
