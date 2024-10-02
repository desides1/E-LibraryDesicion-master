<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\BookController;
use App\Http\Controllers\Backend\PublisherController;
use App\Http\Controllers\Backend\BudgetBookController;
use App\Http\Controllers\Backend\UserManagementController;
use App\Http\Controllers\Backend\WeightCriteriaController;
use App\Http\Controllers\Backend\BookAlternativeController;
use App\Http\Controllers\Backend\RecommendationBookController;

Route::group(['middleware' => ['auth', 'role:Pustakawan|Kepala Perpustakaan']], function () {
    Route::resource('user-management', UserManagementController::class);
    Route::post('/user-management/{user}/toggle-status', [UserManagementController::class, 'toggleStatus']);
    Route::post('/user-management/{user}/toggle-role', [UserManagementController::class, 'toggleRole']);
    Route::post('/user-management/{id}/reset-password', [UserManagementController::class, 'updatePassword']);
    Route::resource('book-publisher', PublisherController::class);
    Route::resource('book-collection', BookController::class);
    Route::resource('weight-criteria', WeightCriteriaController::class);
    Route::resource('book-budget', BudgetBookController::class);
    Route::resource('user-alternative', BookAlternativeController::class);
    // Route::get('book-budget/print/{id}', [BudgetBookController::class, 'print']);
    Route::resource('book-recommendation', RecommendationBookController::class);
    Route::get('book-recommendations/print', [RecommendationBookController::class, 'print']);
});
