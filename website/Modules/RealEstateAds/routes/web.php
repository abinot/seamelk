<?php

use Illuminate\Support\Facades\Route;
use Modules\RealEstateAds\Http\Controllers\RealEstateAdsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('realestateads', RealEstateAdsController::class)->names('realestateads');
});
