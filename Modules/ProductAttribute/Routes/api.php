<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\ProductAttribute\Http\Controllers\API\Admin\ProductAttributeController;

/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('product-attributes')->group(function(){
        Route::get('/', [ProductAttributeController::class,'index'])->name('api.admin.product-attributes.index');    
        Route::get('/get-all-product-attributes-paginate', [ProductAttributeController::class,'getAllProductAttributesPaginate'])->name('api.admin.product-attributes.get-all-product-attributes-paginate'); 
        
        Route::get('/get-product-attributes-for-product/{productId}', [ProductAttributeController::class,'getProductAttributesForProduct'])->name('api.admin.product-attributes.get-product-attributes-for-product');
        Route::get('trash', [ProductAttributeController::class,'trash'])->name('api.admin.product-attributes.trash');
        Route::get('restore-all', [ProductAttributeController::class,'restoreAll'])->name('api.admin.product-attributes.restore-all');
        Route::get('restore/{id}', [ProductAttributeController::class,'restore'])->name('api.admin.product-attributes.restore');
        Route::post('store', [ProductAttributeController::class,'store'])->name('api.admin.product-attributes.store');
        Route::post('store-many-attributes', [ProductAttributeController::class,'saveManyAttributes'])->name('api.admin.product-attributes.save-many-attributes');
        Route::post('update-many-attributes/{productId}', [ProductAttributeController::class,'updateManyAttributes'])->name('api.admin.product-attributes.update-many-attributes');
        Route::get('delete-many-attributes/{id}', [ProductAttributeController::class,'deleteManyAttributes'])->name('api.admin.product-attributes.delete-many-attributes');
      
        Route::post('save-details-array-attribute', [ProductAttributeController::class,'saveDetailsArrayAttribute'])->name('api.admin.product-attributes.save-details-array-attribute');
        Route::post('update-details-array-attribute/{id}', [ProductAttributeController::class,'updateDetailsArrayAttribute'])->name('api.admin.product-attributes.update-details-array-attribute');

        Route::get('show/{id}', [ProductAttributeController::class,'show'])->name('api.admin.product-attributes.show');
        Route::post('update/{id}', [ProductAttributeController::class,'update'])->name('api.admin.product-attributes.update');
        // Route::get('destroy/{id}/{name}/{attrs}', [ProductAttributeController::class,'destroyAttr'])->name('api.admin.product-attributes.destroy');        
        Route::get('destroy/{id}', [ProductAttributeController::class,'destroy'])->name('api.admin.product-attributes.destroy');        
        Route::get('force-delete/{id}', [ProductAttributeController::class,'forceDelete'])->name('api.admin.product-attributes.force-delete');
    });
});