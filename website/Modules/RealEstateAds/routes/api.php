<?php

use Illuminate\Support\Facades\Route;
use Modules\RealEstateAds\Http\Controllers\RealEstateAdsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('realestateads', RealEstateAdsController::class)->names('realestateads');
});
