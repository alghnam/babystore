<?php

use Modules\Product\Http\Controllers\API\Admin\ProductController as ProductControllerAdmin;
use Modules\Product\Http\Controllers\API\User\ProductController as ProductControllerUser;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('products')->group(function(){
        Route::get('/', [ProductControllerAdmin::class,'index'])->name('api.admin.products.index');  
                Route::get('/count', [ProductControllerAdmin::class,'countData'])->name('api.admin.products.count-data');    

                    Route::get('search/{word}', [ProductControllerAdmin::class,'search'])->name('api.admin.products.search');    

        Route::get('some', [ProductControllerAdmin::class,'someProducts'])->name('api.admin.products.some-products');    
  
        Route::get('/get-all-paginates', [ProductControllerAdmin::class,'getAllPaginates'])->name('api.admin.products.get-all-products-paginate');
        
        Route::get('/get-products-for-category/{id}', [ProductControllerAdmin::class,'getProductsForCategory'])->name('api.admin.categories.get-products-for-category');
        Route::get('/main-category-product/{id}', [ProductControllerAdmin::class,'mainCategoryProduct'])->name('api.admin.categories.main-category-product');
        
        Route::get('trash', [ProductControllerAdmin::class,'trash'])->name('api.admin.products.trash');
        Route::get('restore-all', [ProductControllerAdmin::class,'restoreAll'])->name('api.admin.products.restore-all');
        Route::get('restore/{id}', [ProductControllerAdmin::class,'restore'])->name('api.admin.products.restore');
        Route::post('store', [ProductControllerAdmin::class,'store'])->name('api.admin.products.store');
        Route::get('show/{id}', [ProductControllerAdmin::class,'show'])->name('api.admin.products.show');
        Route::get('show-images-product/{id}', [ProductControllerAdmin::class,'showImagesProduct'])->name('api.admin.products.show-images-product');
        
        
        Route::get('product-attributes/{id}', [ProductControllerAdmin::class,'productAttributes'])->name('api.admin.product-attributes.product-attributes');
        // Route::get('similars-product/{id}', [ProductControllerAdmin::class,'similarsProduct'])->name('api.admin.similars-product.similars-product');
        Route::get('product-array-attributes/{id}', [ProductControllerAdmin::class,'productArrayAttributes'])->name('api.admin.product-attributes.product-array-attributes');
            Route::get('search-for-similar/{word}', [ProductControllerAdmin::class,'searchForSimilars'])->name('api.admin.products.search-for-similar');    

        Route::post('update/{id}', [ProductControllerAdmin::class,'update'])->name('api.admin.products.update');
        Route::get('delete-image/{id}', [ProductControllerAdmin::class,'deleteImage'])->name('api.admin.products.delete-image');
        
        Route::post('products-in-inventory/{id}', [ProductControllerAdmin::class,'productsInInventory'])->name('api.admin.products.inventory');
        Route::get('destroy/{id}', [ProductControllerAdmin::class,'destroy'])->name('api.admin.products.destroy');        
        Route::get('force-delete/{id}', [ProductControllerAdmin::class,'forceDelete'])->name('api.admin.products.force-delete');

        
    });
});

/**************************Routes User***************************** */
Route::prefix('products')->group(function(){
            Route::get('/get-location', [ProductControllerUser::class,'getLocation'])->name('api.products.get-location');    
            Route::get('/get-all-data-products-in-home', [ProductControllerUser::class,'getAllDataProductsInHome'])->name('api.admin.products.get-all-data-products-in-home');    

    Route::get('more-sale-products', [ProductControllerUser::class,'getMoreSaleProducts'])->name('api.admin.products.more-sale-products');    
    Route::get('modern-products', [ProductControllerUser::class,'getModernProducts'])->name('api.admin.products.modern-products');    
    Route::get('offers-products', [ProductControllerUser::class,'getOffersProducts'])->name('api.admin.products.offers-products');    
    Route::get('products-category/{categoryId}', [ProductControllerUser::class,'getProductsForCategory'])->name('api.admin.products.products-category');
            Route::get('/get-products-for-sub-category/{id}', [ProductControllerUser::class,'getProductsForSubCategoryTable'])->name('api.admin.categories.get-products-for-sub-category');

            Route::get('search/{word}', [ProductControllerUser::class,'search'])->name('api.admin.products.user-search');    
            Route::get('search-products-category/{categoryId}/{word}', [ProductControllerUser::class,'searchProductsSpesificCategorySpesificWord'])->name('api.admin.products.search-products-category');    
            
    Route::get('search-more-sale/{word}', [ProductControllerUser::class,'searchMoreSale'])->name('api.admin.products.search-more-sale');    
    Route::get('search-modern/{word}', [ProductControllerUser::class,'searchModern'])->name('api.admin.products.search-modern');    
    Route::get('search-offers/{word}', [ProductControllerUser::class,'searchOffers'])->name('api.admin.products.search-offers');    
    Route::get('search-price/{word}/{price1}/{price2}', [ProductControllerUser::class,'searchProductsSpesificPriceSpesificWord'])->name('api.admin.products.search-price');    
    Route::get('search-price-more-sale/{word}/{price1}/{price2}', [ProductControllerUser::class,'searchPriceMoreSale'])->name('api.admin.products.search-price-more-sale');    
    Route::get('search-price-modern/{word}/{price1}/{price2}', [ProductControllerUser::class,'searchPriceModern'])->name('api.admin.products.search-price-modern');    
    Route::get('search-price-offers/{word}/{price1}/{price2}', [ProductControllerUser::class,'searchPriceOffers'])->name('api.admin.products.search-price-offers');    
    Route::get('show-product/{id}', [ProductControllerUser::class,'showProductWithRelations'])->name('api.admin.products.search-relations');    
    Route::get('show-attributes-product/{id}', [ProductControllerUser::class,'showAttributesProduct'])->name('api.admin.products.show-attributes-product');    
    Route::get('show-details-product-attribute/{id}', [ProductControllerUser::class,'showDetailsProductArrayAttribute'])->name('api.admin.products.show-details-product-attribute');    

//    Route::get('get-address-from-ip', [GeoLocationController::class, 'index']);
    Route::post('show-id-attribute/{productId}', [ProductControllerUser::class,'showAttributeIdForArray'])->name('api.products.show-id-attribute');    


});
