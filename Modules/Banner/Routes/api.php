<?php

use Illuminate\Http\Request;

use Modules\Banner\Http\Controllers\API\Admin\BannerController as BannerControllerAdmin;
use Modules\Banner\Http\Controllers\API\User\BannerController as BannerControllerUser;

/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('banners')->group(function(){
        Route::get('/get-all-paginates', [BannerControllerAdmin::class,'getAllPaginates'])->name('api.admin.banners.get-all');
        Route::get('trash', [BannerControllerAdmin::class,'trash'])->name('api.admin.banners.trash');
        Route::get('restore-all', [BannerControllerAdmin::class,'restoreAll'])->name('api.admin.banners.restore-all');
        Route::get('restore/{id}', [BannerControllerAdmin::class,'restore'])->name('api.admin.banners.restore');
        Route::post('store', [BannerControllerAdmin::class,'store'])->name('api.admin.banners.store');
        Route::get('show/{id}', [BannerControllerAdmin::class,'show'])->name('api.admin.banners.show');
        Route::post('update/{id}', [BannerControllerAdmin::class,'update'])->name('api.admin.banners.update');
        Route::get('destroy/{id}', [BannerControllerAdmin::class,'destroy'])->name('api.admin.banners.destroy');        
        Route::get('force-delete/{id}', [BannerControllerAdmin::class,'forceDelete'])->name('api.admin.banners.force-delete');
                Route::get('delete-image/{id}', [BannerControllerAdmin::class,'deleteImage'])->name('api.admin.banners.delete-image');

    });
});

/**************************Routes User***************************** */
    Route::prefix('banners')->namespace('API')->group(function(){
               Route::get('/get-all-banners-for-user-paginate', [BannerControllerUser::class,'getAllBannersForUserPaginate'])->name('api.user.banners.get-all-banners-for-user-paginate');        
 
    });