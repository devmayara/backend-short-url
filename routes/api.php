<?php

use App\Http\Controllers\ShortUrlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando!']);
});

Route::get('/{shortCode}', [ShortUrlController::class, 'redirect']);
Route::post('/shorten', [ShortUrlController::class, 'store']);