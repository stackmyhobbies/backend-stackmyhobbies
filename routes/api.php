<?php

use App\Http\Controllers\ContentStatusController;
use App\Http\Controllers\ContentTypeController;
use App\Models\ContentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/content-types', [ContentTypeController::class, 'index']);

Route::get('/content-types/{id}', [ContentTypeController::class, 'show']);
Route::post('/content-types', [ContentTypeController::class, 'store']);
Route::put('/content-types/{id}/edit', [ContentTypeController::class, 'update']);


Route::delete('/content-types/{contenttype}', [ContentTypeController::class, 'destroy']);






Route::get('/content-statuses', [ContentStatusController::class, 'index']);
Route::get('/content-status/{id}', [ContentStatusController::class, 'show']);


Route::post('/content-statuses', [ContentStatusController::class, 'store']);
Route::put('/content-statuses/{id}/edit', [ContentStatusController::class, 'update']);
Route::delete('/content-statuses/{id}', [ContentStatusController::class, 'destroy']);
