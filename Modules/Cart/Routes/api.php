<?php

use Modules\Cart\Http\Controllers\API\Admin\CartController as CartControllerAdmin;
use Modules\Cart\Http\Controllers\API\User\CartController as CartControllerUser;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('carts')->group(function(){
        Route::get('/', [CartControllerAdmin::class,'index'])->name('api.admin.carts.index');    
        Route::get('/get-all-paginates', [CartControllerAdmin::class,'getAllPaginates'])->name('api.admin.carts.get-all-carts-paginate');
                    Route::get('/get-all-product-array-attributes-cart-paginates/{id}', [CartControllerAdmin::class,'getAllProductArrayAttributesCartPaginates'])->name('api.admin.pushnotifications.get-all-product-array-attributes-cart-paginates');

        Route::get('trash', [CartControllerAdmin::class,'trash'])->name('api.admin.carts.trash');
        Route::get('restore-all', [CartControllerAdmin::class,'restoreAll'])->name('api.admin.carts.restore-all');
        Route::get('restore/{id}', [CartControllerAdmin::class,'restore'])->name('api.admin.carts.restore');
        Route::post('store', [CartControllerAdmin::class,'store'])->name('api.admin.carts.store');
        Route::get('show/{id}', [CartControllerAdmin::class,'show'])->name('api.admin.carts.show');
        Route::post('update/{id}', [CartControllerAdmin::class,'update'])->name('api.admin.carts.update');
        
        Route::get('destroy/{id}', [CartControllerAdmin::class,'destroy'])->name('api.admin.carts.destroy');        
        Route::get('force-delete/{id}', [CartControllerAdmin::class,'forceDelete'])->name('api.admin.carts.force-delete');
    });
});

/**************************Routes User***************************** */
Route::prefix('carts')->group(function(){
    Route::get('get-cart', [CartControllerUser::class,'getCartUser'])->name('api.products.get-cart');
    Route::get('get-count-products-cart', [CartControllerUser::class,'getCountProductsCart'])->name('api.products.get-count-products-cart');
    Route::post('select-attribute/{productId}', [CartControllerUser::class,'selectAttribute'])->name('api.products.select-attribute');
    Route::post('add-to-cart/{productId}', [CartControllerUser::class,'addToCart'])->name('api.products.add-to-cart-u');
    Route::get('delete-product-from-cart/{productId}', [CartControllerUser::class,'removeProductFromCart'])->name('api.products.delete-product-from-cart');
    Route::get('delete-all-quantities-product/{productId}', [CartControllerUser::class,'deleteAllQuantitiesProduct'])->name('api.products.delete-all-quantities-product');
});

