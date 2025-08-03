<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\ContentItemController;
use App\Http\Controllers\ContentStatusController;
use App\Http\Controllers\ContentTypeController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Models\ContentStatus;
use Illuminate\Support\Facades\Route;


//*rutas publicas
Route::post('/auth/sign-in', [SessionController::class, 'store'])->name('login');
Route::post('/auth/sign-up', RegisterController::class);

Route::middleware(['auth:sanctum'])->group(function () {

    //* ruta privada autenticada para cerrar sesion
    Route::post('/auth/sign-out', [SessionController::class, 'destroy']);

    //* ruta privada autenticada para gestionar el item
    Route::get('/content-items', [ContentItemController::class, 'index']);
    Route::post('/content-items', [ContentItemController::class, 'store']);
    Route::get('/content-items/{slug}', [ContentItemController::class, 'show']);
    Route::put('/content-items/{id}/edit', [ContentItemController::class, 'update']);
    Route::delete('/content-items/{id}', [ContentItemController::class, 'destroy']);


    //**TODO Crear ruta admin */
    //** ruta privada que son necesaria para el usuario para crear o consultar content-items
    Route::get('/tags', [TagController::class, 'index']);  //* usado para carga select o checkbox
    Route::get('/tags/{slug}', [TagController::class, 'show']); //* para visualizar content-items perteneciente a un tag en especifico

    Route::get('/content-statuses', [ContentStatusController::class, 'indexForUser']);
    Route::get('/content-types', [ContentTypeController::class, 'index']); //* usado para carga select

    Route::prefix('admin')->middleware(['is_admin'])->group(function () {

        //** types 😠
        Route::get('/content-types/{id}', [ContentTypeController::class, 'show']);
        Route::post('/content-types', [ContentTypeController::class, 'store']);
        Route::put('/content-types/{id}/edit', [ContentTypeController::class, 'update']);
        Route::delete('/content-types/{contenttype}', [ContentTypeController::class, 'destroy']);

        //* users
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}/edit', [UserController::class, 'update']);
        route::delete('/users/{id}', [UserController::class, 'destroy']);

        //** status 😠
        Route::get('/content-statuses', [ContentStatusController::class, 'index']);
        Route::get('/content-status/{id}', [ContentStatusController::class, 'show']);
        Route::post('/content-statuses', [ContentStatusController::class, 'store']);
        Route::put('/content-statuses/{id}/edit', [ContentStatusController::class, 'update']);
        Route::delete('/content-statuses/{id}', [ContentStatusController::class, 'destroy']);

        //*tags
        Route::post('/tags', [TagController::class, 'store']);
        Route::put('/tags/{id}/edit', [TagController::class, 'update']);
        Route::delete('/tags/{id}', [TagController::class, 'destroy']);
    });
});
