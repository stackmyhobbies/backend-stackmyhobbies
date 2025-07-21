<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\ContentItemController;
use App\Http\Controllers\ContentStatusController;
use App\Http\Controllers\ContentTypeController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



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

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::put('/users/{id}/edit', [UserController::class, 'update']);

route::delete('/users/{id}', [UserController::class, 'destroy']);


Route::get('/tags', [TagController::class, 'index']);
Route::get('/tags/{slug}', [TagController::class, 'show']);
Route::post('/tags', [TagController::class, 'store']);
Route::put('/tags/{id}/edit', [TagController::class, 'update']);
Route::delete('/tags/{id}', [TagController::class, 'destroy']);


Route::get('/content-items', [ContentItemController::class, 'index']);
Route::post('/content-items', [ContentItemController::class, 'store']);
Route::get('/content-items/{slug}', [ContentItemController::class, 'show']);
Route::put('/content-items/{id}/edit', [ContentItemController::class, 'update']);
Route::delete('/content-items/{id}', [ContentItemController::class, 'destroy']);


Route::post('/auth/sign-in', [SessionController::class, 'store'])->name('login');
Route::post('/auth/sign-up', RegisterController::class);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/auth/sign-out', [SessionController::class, 'destroy']);

    Route::get('/tags', [TagController::class, 'index']);
    Route::middleware(['is_admin'])->group(function () {});

    // otras rutas...
});


// // Rutas públicas
// Route::get('/content-items', [ContentItemController::class, 'index']);
// Route::get('/content-items/{slug}', [ContentItemController::class, 'show']);
// Route::post('/auth/sign-in', [SessionController::class, 'store']);
// Route::post('/auth/sign-up', RegisterController::class);

// // Rutas autenticadas
// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::post('/auth/sign-out', [SessionController::class, 'destroy']);

//     // ContentItems: accesos controlados por policies
//     Route::post('/content-items', [ContentItemController::class, 'store']);
//     Route::put('/content-items/{id}/edit', [ContentItemController::class, 'update']);
//     Route::delete('/content-items/{id}', [ContentItemController::class, 'destroy']);

//     // Rutas solo para administradores
//     Route::middleware(['is_admin'])->group(function () {
//         Route::resource('/content-types', ContentTypeController::class)->except('show');
//         Route::resource('/content-statuses', ContentStatusController::class)->except('show');
//         Route::resource('/tags', TagController::class)->except('show');
//         Route::resource('/users', UserController::class);
//     });
// });