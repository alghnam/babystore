<?php

use Illuminate\Support\Facades\Route;
use Modules\Seo\Http\Controllers\API\SeoController;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('seos')->group(function(){
        Route::post('store', [SeoController::class,'store'])->name('api.admin.seos.store');
        Route::post('update/{id}', [SeoController::class,'update'])->name('api.admin.seos.update');
    });
});
