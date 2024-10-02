<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    $statusCode = app('Illuminate\Http\Response')->status();

    switch ($statusCode) {
        case 404:
            return response()->view('errors.404', [], 404);
            break;
        case 403:
            return response()->view('errors.403', [], 403);
            break;
        case 500:
            return response()->view('errors.500', [], 500);
            break;
        default:
            return response()->view('errors.404', [], 404);
            break;
    }
});
