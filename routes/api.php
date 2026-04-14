<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\ContentItemController;
use App\Http\Controllers\ProgressStatusController;
use App\Http\Controllers\ContentTypeController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\EmailVerificationController;
use Illuminate\Support\Facades\Route;



//* consejos

/***
 *
 * TODO PENDIENTE RUTA
 ** Un pequeño consejo: Veo que tu última ruta es api/tags/{slug}/content-items.
 **  En esa ruta NO deberías mostrar el detail_url, solo el thumbnail,
 ** ya que suele ser una vista de lista filtrada por
 ** etiquetas. Por eso el routeIs es tu mejor amigo aquí para mantener la API ligera.
 */
//*rutas publicas


Route::post('/auth/sign-in', [SessionController::class, 'store'])->name('login');
Route::post('/auth/sign-up', RegisterController::class);

Route::post('/auth/forgot-password', ForgotPasswordController::class);
Route::post('/auth/reset-password', ResetPasswordController::class);

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

//! Ruta para reenvío de verificación (requiere token específico)
Route::post('/email/verify/resend', [EmailVerificationController::class, 'resendVerificationEmail'])
    ->middleware(['auth:sanctum', 'abilities:email:verify:send'])
    ->name('verification.resend');

Route::middleware(['auth:sanctum'])->group(function () {




    //* ruta privada autenticada para cerrar sesion
    Route::post('/auth/sign-out', [SessionController::class, 'destroy']);
    Route::get('/auth/check-session', [SessionController::class, 'check']);
    //* ruta privada autenticada para gestionar el item
    Route::get('/content-items', [ContentItemController::class, 'indexForUser']);
    Route::post('/content-items', [ContentItemController::class, 'storeForUser']);
    Route::put('/content-items/{id}/edit', [ContentItemController::class, 'updateForUser']);

    //*TODO PENDIENTE , ORGANIZAR EL SHOW
    Route::get('/content-items/{slug}', [ContentItemController::class, 'show'])->name('content-item.show');
    Route::delete('/content-items/{id}', [ContentItemController::class, 'destroyForUser']);

    Route::get('/progress-statuses', [ProgressStatusController::class, 'indexForUser']);
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

        //** progress statuses
        Route::get('/progress-statuses', [ProgressStatusController::class, 'index']);
        Route::get('/progress-statuses/{id}', [ProgressStatusController::class, 'show']);
        Route::post('/progress-statuses', [ProgressStatusController::class, 'store']);
        Route::put('/progress-statuses/{id}/edit', [ProgressStatusController::class, 'update']);
        Route::delete('/progress-statuses/{id}', [ProgressStatusController::class, 'destroy']);

        //*tags
        Route::get('/tags', [TagController::class, 'index']);
        Route::post('/tags', [TagController::class, 'store']);
        Route::put('/tags/{id}/edit', [TagController::class, 'update']);
        Route::delete('/tags/{id}', [TagController::class, 'destroy']);
        Route::get('/tags/{slug}', [TagController::class, 'show']);
    });
});
