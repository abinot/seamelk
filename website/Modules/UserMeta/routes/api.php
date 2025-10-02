<?php

use Illuminate\Support\Facades\Route;
use Modules\UserMeta\Http\Controllers\UserMetaController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('usermetas', UserMetaController::class)->names('usermeta');
});
