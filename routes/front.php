<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\BookRequestController;
use App\Http\Controllers\Front\InformationController;
use App\Http\Controllers\Front\BookCollectionController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/front-book', [BookCollectionController::class, 'index']);
Route::get('/front-book/{id}', [BookCollectionController::class, 'show']);
Route::get('/front-request', [BookRequestController::class, 'index']);
Route::post('/front-request', [BookRequestController::class, 'store']);
Route::post('/front-requests', [BookRequestController::class, 'storeSuggestion']);
Route::get('/front-requests', [BookRequestController::class, 'getCreateRequest']);
Route::get('/front-request/{id}', [BookRequestController::class, 'show']);
Route::get('/front-request-user/{id}', [BookRequestController::class, 'searchData'])->name('front-request.searchData');
Route::get('/front-request-book/{id}', [BookRequestController::class, 'getBookDetail']);
Route::get('/front-information', [InformationController::class, 'index']);
