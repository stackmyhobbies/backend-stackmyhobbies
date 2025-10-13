<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\ContentItemController;
use App\Http\Controllers\ContentStatusController;
use App\Http\Controllers\ContentTypeController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


//*rutas publicas
Route::post('/auth/sign-in', [SessionController::class, 'store'])->name('login');
Route::post('/auth/sign-up', RegisterController::class);

Route::post('/forgot-password', ForgotPasswordController::class);

Route::post('/reset-password', ResetPasswordController::class);

Route::middleware(['auth:sanctum'])->group(function () {

    //* ruta privada autenticada para cerrar sesion
    Route::post('/auth/sign-out', [SessionController::class, 'destroy']);

    //* ruta privada autenticada para gestionar el item
    Route::get('/content-items', [ContentItemController::class, 'indexForUser']);
    Route::post('/content-items', [ContentItemController::class, 'storeForUser']);
    Route::put('/content-items/{id}/edit', [ContentItemController::class, 'updateForUser']);

    //*TODO PENDIENTE , ORGANIZAR EL SHOW
    Route::get('/content-items/{slug}', [ContentItemController::class, 'show']);
    Route::delete('/content-items/{id}', [ContentItemController::class, 'destroyForUser']);

    Route::get('/content-statuses', [ContentStatusController::class, 'indexForUser']);
    Route::get('/content-types', [ContentTypeController::class, 'indexForUser']);

    Route::get('/tags', [TagController::class, 'indexForUser']);
    Route::get('/tags/{slug}', [TagController::class, 'showForUser']);
    Route::get('/tags/{slug}/content-items', [TagController::class, 'showTagWithContentItem']);



    Route::prefix('admin')->middleware(['is_admin'])->group(function () {


        //TODO COMPLETAR RUTAS CONTENT-ITESM 21 DE SEPT 2025
        Route::get('/content-items', [ContentItemController::class, 'index']);
        Route::get('/content-items/{slug}', [ContentItemController::class, 'show']);
        Route::post('/content-items', [ContentItemController::class, 'store']);
        Route::put('/content-items/{id}/edit', [ContentItemController::class, 'update']);
        Route::delete('/content-items/{id}', [ContentItemController::class, 'destroy']);



        //** types 😠
        Route::get('/content-types', [ContentTypeController::class, 'index']);
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
        Route::get('/tags', [TagController::class, 'index']);
        Route::post('/tags', [TagController::class, 'store']);
        Route::put('/tags/{id}/edit', [TagController::class, 'update']);
        Route::delete('/tags/{id}', [TagController::class, 'destroy']);
        Route::get('/tags/{slug}', [TagController::class, 'show']);
    });
});
