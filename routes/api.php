<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormSubmissionController;

Route::middleware('throttle:10,1')->group(function () {
    Route::post('/forms/submit', [FormSubmissionController::class, 'store']);
    Route::get('/forms/submit', function () {
        return response()->json(['message' => 'GET route is working!!!!!!!']);
    });
});
