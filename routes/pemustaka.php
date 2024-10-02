<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AlternativeBookUserController;
use App\Http\Controllers\Backend\UserRealizationBookController;

Route::group(['middleware' => ['auth', 'role:Pemustaka']], function () {
    Route::resource('/user-book', AlternativeBookUserController::class);
    Route::resource('/user-realization', UserRealizationBookController::class);
});
