<?php

use Illuminate\Support\Facades\Route;
use Modules\UserMeta\Http\Controllers\UserMetaController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('usermetas', UserMetaController::class)->names('usermeta');
});
