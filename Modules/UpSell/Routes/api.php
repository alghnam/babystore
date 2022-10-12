<?php

use Illuminate\Http\Request;
use Modules\UpSell\Http\Controllers\API\Admin\UpSellController as UpSellControllerAdmin;
use Modules\UpSell\Http\Controllers\API\User\UpSellController as UpSellControllerUser;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('upsells')->group(function(){
        Route::get('/', [UpSellControllerAdmin::class,'index'])->name('api.admin.upsells.index');    
                Route::get('/count', [UpSellControllerAdmin::class,'countData'])->name('api.admin.upsells.count-data');    

        Route::get('/get-all-paginates', [UpSellControllerAdmin::class,'getAllPaginates'])->name('api.admin.upsells.get-all-upsells-paginate');
            
        Route::get('trash', [UpSellControllerAdmin::class,'trash'])->name('api.admin.upsells.trash');
        Route::get('restore-all', [UpSellControllerAdmin::class,'restoreAll'])->name('api.admin.upsells.restore-all');
        Route::get('restore/{id}', [UpSellControllerAdmin::class,'restore'])->name('api.admin.upsells.restore');
        Route::post('store', [UpSellControllerAdmin::class,'store'])->name('api.admin.upsells.store');
        Route::get('show/{id}', [UpSellControllerAdmin::class,'show'])->name('api.admin.upsells.show');
        Route::post('update/{id}', [UpSellControllerAdmin::class,'update'])->name('api.admin.upsells.update');
        Route::post('update-sells-product/{product_id}', [UpSellControllerAdmin::class,'updateUpsellsProduct'])->name('api.admin.upsells.update-sells-product');
        
        
        Route::post('update/{id}/{productId}', [UpSellControllerAdmin::class,'update'])->name('api.admin.upsells.update');
        
        Route::get('upsells-product/{productid}', [UpSellControllerAdmin::class,'upsellsProduct'])->name('api.admin.upsells.upsells-product');        
        Route::get('delete-upsell-product/{productid}/{upsell}', [UpSellControllerAdmin::class,'deleteUpsellProduct'])->name('api.admin.upsells.delete-upsell-product');        
        Route::get('destroy/{id}', [UpSellControllerAdmin::class,'destroy'])->name('api.admin.upsells.destroy');        
        Route::get('force-delete/{id}', [UpSellControllerAdmin::class,'forceDelete'])->name('api.admin.upsells.force-delete');
    });
});

Route::prefix('upsells')->middleware(['auth:api'])->namespace('API')->group(function(){
            Route::get('upsells-product/{productid}', [UpSellControllerUser::class,'upsellsProduct'])->name('api.upsells.upsells-product');        
            Route::get('product-attrs/{productid}', [UpSellControllerUser::class,'productAttrs'])->name('api.upsells.product-attrs');        

});