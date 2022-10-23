<?php

use Modules\SimilarProduct\Http\Controllers\API\Admin\SimilarProductController as SimilarProductControllerAdmin;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('similar-products')->group(function(){
        Route::post('store', [SimilarProductControllerAdmin::class,'store'])->name('api.admin.similar-similar-products.store');
        Route::get('destroy/{id}', [SimilarProductControllerAdmin::class,'destroy'])->name('api.admin.similar-products.destroy');     
                Route::get('show/{id}', [SimilarProductControllerAdmin::class,'show'])->name('api.admin.similar-products.show');
                Route::get('similars-product/{id}', [SimilarProductControllerAdmin::class,'similarsProduct'])->name('api.admin.similar-products.similars-product');

        Route::post('update/{id}', [SimilarProductControllerAdmin::class,'update'])->name('api.admin.similar-similar-products.update');
        Route::get('destroy/{id}/{similarId}', [SimilarProductControllerAdmin::class,'destroy'])->name('api.admin.similar-products.destroy-similar');        
       
    });
});
