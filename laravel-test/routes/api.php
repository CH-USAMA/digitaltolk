<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\TranslationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/translations', [TranslationController::class, 'store']);
Route::put('/translations/{id}', [TranslationController::class, 'update']);
Route::get('/translations', [TranslationController::class, 'index']);
Route::get('/translations/export', [TranslationController::class, 'export']);

