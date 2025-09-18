<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormSubmissionController;

Route::post('/forms/submit', [FormSubmissionController::class, 'store']);
// Temporary GET route for debugging
Route::get('/forms/submit', function () {
    return response()->json(['message' => 'GET route is working!!!!!!!']);
});
