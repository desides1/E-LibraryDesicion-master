<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;


Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// logout
Route::post('logout', [LoginController::class, 'destroy'])
    ->name('logout');
Route::get('logout', [LoginController::class, 'destroy'])
    ->name('logout');
    // end logout
