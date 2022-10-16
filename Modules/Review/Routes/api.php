<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Review\Http\Controllers\API\Admin\ReviewController as ReviewControllerAdmin;
use Modules\Review\Http\Controllers\API\User\ReviewController as ReviewControllerUser;

/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('reviews')->group(function(){
        Route::get('/', [ReviewControllerAdmin::class,'index'])->name('api.admin.reviews.index');    
        Route::get('/get-all-paginates', [ReviewControllerAdmin::class,'getAllPaginates'])->name('api.admin.reviews.get-all-reviews-paginate');
        Route::get('/count-data', [ReviewControllerAdmin::class,'countData'])->name('api.admin.reviews.count-data');
        Route::get('/get-reviews-product/{productId}', [ReviewControllerAdmin::class,'getReviewsProduct'])->name('api.admin.reviews.get-reviews-product');
        Route::get('trash', [ReviewControllerAdmin::class,'trash'])->name('api.admin.reviews.trash');
        Route::get('restore-all', [ReviewControllerAdmin::class,'restoreAll'])->name('api.admin.reviews.restore-all');
        Route::get('restore/{id}', [ReviewControllerAdmin::class,'restore'])->name('api.admin.reviews.restore');
        Route::post('store', [ReviewControllerAdmin::class,'store'])->name('api.admin.reviews.store');
        Route::get('show/{id}', [ReviewControllerAdmin::class,'show'])->name('api.admin.reviews.show');
        Route::post('update/{id}', [ReviewControllerAdmin::class,'update'])->name('api.admin.reviews.update');
        Route::get('destroy/{id}', [ReviewControllerAdmin::class,'destroy'])->name('api.admin.reviews.destroy');        
        Route::get('force-delete/{id}', [ReviewControllerAdmin::class,'forceDelete'])->name('api.admin.reviews.force-delete');
    });
});

    Route::prefix('reviews')->middleware(['auth:api'])->group(function(){
                Route::post('add-review/{productId}', [ReviewControllerUser::class,'addReview'])->name('api.admin.reviews.add-review');

    });
