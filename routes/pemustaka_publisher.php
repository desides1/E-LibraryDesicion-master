<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ProfileController;

Route::group(['middleware' => ['auth', 'role:Pemustaka|Penerbit']], function () {
    Route::resource('/profile-management', ProfileController::class);
    Route::post('/profile-management', [ProfileController::class, 'updatePassword'])->name('profile-management.updatePassword');
});
