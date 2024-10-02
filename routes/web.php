<?php

use App\Http\Controllers\Backend\BookAlternativeController;
use App\Http\Controllers\Backend\BookController;
use App\Http\Controllers\Backend\BookPublisherController;
use App\Http\Controllers\Backend\BudgetBookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PublisherController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth', 'role:Pustakawan|Pemustaka|Penerbit']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

Route::group(['middleware' => ['auth', 'role:Pustakawan']], function () {
    Route::get('/book-collection/export-excel', [BookController::class, 'exportExcel']);
    Route::get('/book-publisher/export-excel', [PublisherController::class, 'exportExcel']);
    Route::get('/user-alternative/export-excel', [BookAlternativeController::class, 'exportExcel']);
    // Route::get('/book-budget/export-excel/{id}', [BudgetBookController::class, 'exportExcel'])->name('book-budget.export-excel');
});

Route::group(['middleware' => ['auth', 'role:Penerbit']], function () {
    Route::get('/book-list/export-excel', [BookPublisherController::class, 'exportExcel']);
    Route::get('/book-list/import-excel', [BookPublisherController::class, 'importExcel']);
    Route::post('/book-list/import-excel', [BookPublisherController::class, 'storeImportExcel']);
});

// Route::get('/storage-link', function () {
//     $target = storage_path('app/public');
//     $shortcut = $_SERVER['DOCUMENT_ROOT'] . '/storage';
//     symlink($target, $shortcut);
// });

require __DIR__ . '/error.php';
require __DIR__ . '/front.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/pemustaka_publisher.php';
require __DIR__ . '/publisher.php';
