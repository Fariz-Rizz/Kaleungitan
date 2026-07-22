<?php

use App\Http\Controllers\Api\ClaimController;
use App\Http\Controllers\Api\ItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Endpoint publik (tanpa autentikasi) untuk resource Items dan Claims.
| Semua route otomatis mendapat prefix "/api" dan diarahkan ke controller
| di App\Http\Controllers\Api.
|
*/

Route::apiResource('items', ItemController::class);

Route::apiResource('claims', ClaimController::class);
Route::post('claims/{claim}/approve', [ClaimController::class, 'approve'])->name('claims.approve');
Route::post('claims/{claim}/reject', [ClaimController::class, 'reject'])->name('claims.reject');
