<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\BookController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\PublisherController;
use App\Http\Controllers\Backend\BudgetBookController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\BookHistoryController;
use App\Http\Controllers\Backend\CategoryBookController;
use App\Http\Controllers\Backend\UserManagementController;
use App\Http\Controllers\Backend\WeightCriteriaController;
use App\Http\Controllers\Backend\BookAlternativeController;
use App\Http\Controllers\Backend\MajorController;
use App\Http\Controllers\Backend\UnitController;
use Maatwebsite\Excel\Row;

Route::group(['middleware' => ['auth', 'role:Pustakawan']], function () {
    // user management
    Route::resource('user-management', UserManagementController::class);
    Route::post('/user-management/{user}/toggle-status', [UserManagementController::class, 'toggleStatus']);
    Route::post('/user-management/{user}/toggle-role', [UserManagementController::class, 'toggleRole']);
    Route::post('/user-management/{id}/reset-password', [UserManagementController::class, 'updatePassword']);
    // end user management

    // book publisher
    Route::resource('book-publisher', PublisherController::class)->only('index');
    Route::get('/book-publisher/detail-publisher/{id}', [PublisherController::class, 'detailPublisher'])->name('detailPublisher');
    // end book publisher

    // book collection library
    Route::resource('book-collection', BookController::class)->except('create');
    Route::post('/book-collection/{book}/toggle-status', [BookController::class, 'toggleStatus']);
    // end book collection library

    // criteria for book recommendation
    Route::resource('weight-criteria', WeightCriteriaController::class);
    Route::post('/weight-criteria/{criteria}/toggle-status', [WeightCriteriaController::class, 'toggleStatus']);
    Route::get('/weight-criteria/edit-criteria', [WeightCriteriaController::class, 'editCriteria']);
    // end criteria for book recommendation

    // book budget for recommendation, payment, and history
    Route::resource('book-budget', BudgetBookController::class);
    Route::get('book-budget/history/{id}', [BudgetBookController::class, 'history'])->name('book-budget.history');
    Route::post('book-budget/history/{id}/update-status-process', [BudgetBookController::class, 'updateStatusProcess']);
    Route::post('book-budget/history/{id}/update-status-realization', [BudgetBookController::class, 'updateStatusRealization']);
    Route::post('book-budget/payment', [BudgetBookController::class, 'payment'])->name('book-budget.payment');
    Route::get('book-budget/print/{id}', [BudgetBookController::class, 'print'])->name('book-budget.print');
    Route::get('book-budget-book({id})', [BudgetBookController::class, 'getBookDetail'])->name('book-budget.book-detail');
    // end book budget for recommendation, payment, and history

    // alternative for user request book
    Route::resource('user-alternative', BookAlternativeController::class)->only(['index', 'show']);
    // end alternative for user request book

    // Route::resource('book-recommendation', RecommendationBookController::class);
    // Route::get('book-recommendations/print', [RecommendationBookController::class, 'print']);

    // role and permission
    Route::resource('role-management', RoleController::class);
    Route::resource('permission-management', PermissionController::class);
    // end role and permission

    // category book classification
    Route::resource('book-classification', CategoryBookController::class);
    // end category book classification

    // book history for book budget
    // Route::resource('book-history', BookHistoryController::class);
    // Route::post('/book-history/{id}/update-status-process', [BookHistoryController::class, 'updateStatusProcess']);
    // Route::post('/book-history/{id}/update-status-realization', [BookHistoryController::class, 'updateStatusRealization']);
    // end book history for book budget

    // Route::resource('/book-realization', BookRealizationController::class);

    // major
    Route::resource('/major', MajorController::class);
    Route::post('/major/{major}/toggle-status', [MajorController::class, 'toggleStatus']);
    // end major 

    // unit
    Route::resource('/unit', UnitController::class);
    Route::post('/unit/{unit}/toggle-status', [UnitController::class, 'toggleStatus']);
    // end unit
});
