<?php

use Illuminate\Support\Facades\Route;
use Modules\RealEstate\Http\Controllers\RealEstateController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/real-estates', function () {
        return view('realestate::index');
    })->name('realestate.index');
    Route::get('/@{real_estate_id}', [RealEstateController::class, 'show']);
});
