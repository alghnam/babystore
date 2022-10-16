<?php

use Illuminate\Http\Request;
use Modules\SystemReview\Http\Controllers\API\Admin\SystemReviewController as SystemReviewControllerAdmin;
use Modules\SystemReview\Http\Controllers\API\Admin\SystemReviewTypeController as SystemReviewTypeControllerAdmin;
use Modules\SystemReview\Http\Controllers\API\User\SystemReviewController as SystemReviewControllerUser;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('system-reviews')->group(function(){
        Route::get('/', [SystemReviewControllerAdmin::class,'index'])->name('api.admin.system-reviews.index');    
        Route::get('/get-all-paginates', [SystemReviewControllerAdmin::class,'getAllPaginates'])->name('api.admin.system-reviews.get-all-system-reviews-paginate');
        Route::get('/count-data', [SystemReviewControllerAdmin::class,'countData'])->name('api.admin.system-reviews.count-data');
            
        Route::get('trash', [SystemReviewControllerAdmin::class,'trash'])->name('api.admin.system-reviews.trash');
        Route::get('restore-all', [SystemReviewControllerAdmin::class,'restoreAll'])->name('api.admin.system-reviews.restore-all');
        Route::get('restore/{id}', [SystemReviewControllerAdmin::class,'restore'])->name('api.admin.system-reviews.restore');
        Route::post('store', [SystemReviewControllerAdmin::class,'store'])->name('api.admin.system-reviews.store');
        Route::get('show/{id}', [SystemReviewControllerAdmin::class,'show'])->name('api.admin.system-reviews.show');
        Route::post('update/{id}', [SystemReviewControllerAdmin::class,'update'])->name('api.admin.system-reviews.update');
        
        Route::get('destroy/{id}', [SystemReviewControllerAdmin::class,'destroy'])->name('api.admin.system-reviews.destroy');        
        Route::get('force-delete/{id}', [SystemReviewControllerAdmin::class,'forceDelete'])->name('api.admin.system-reviews.force-delete');
    });
    Route::prefix('system-review-types')->group(function(){
        Route::get('/', [SystemReviewTypeControllerAdmin::class,'index'])->name('api.admin.system-reviews-types.index');    
        Route::get('/get-all-paginates', [SystemReviewTypeControllerAdmin::class,'getAllPaginates'])->name('api.admin.system-reviews-types.get-all-system-reviews-paginate');
            
        Route::get('trash', [SystemReviewTypeControllerAdmin::class,'trash'])->name('api.admin.system-review-types.trash');
        Route::get('restore-all', [SystemReviewTypeControllerAdmin::class,'restoreAll'])->name('api.admin.system-review-types.restore-all');
        Route::get('restore/{id}', [SystemReviewTypeControllerAdmin::class,'restore'])->name('api.admin.system-review-types.restore');
        Route::post('store', [SystemReviewTypeControllerAdmin::class,'store'])->name('api.admin.system-review-types.store');
        Route::get('show/{id}', [SystemReviewTypeControllerAdmin::class,'show'])->name('api.admin.system-review-types.show');
        Route::post('update/{id}', [SystemReviewTypeControllerAdmin::class,'update'])->name('api.admin.system-review-types.update');
        
        Route::get('destroy/{id}', [SystemReviewTypeControllerAdmin::class,'destroy'])->name('api.admin.system-review-types.destroy');        
        Route::get('force-delete/{id}', [SystemReviewTypeControllerAdmin::class,'forceDelete'])->name('api.admin.system-review-types.force-delete');
    });
});

Route::prefix('system-reviews')->middleware(['auth:api'])->namespace('API')->group(function(){
      Route::get('get-types', [SystemReviewControllerUser::class,'getTypes'])->name('api.system-reviews.get-types');
      Route::post('add-system-review', [SystemReviewControllerUser::class,'addSystemReview'])->name('api.system-reviews.add-system-review');
      
});