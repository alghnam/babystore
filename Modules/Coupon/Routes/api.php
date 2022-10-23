<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Coupon\Http\Controllers\API\Admin\CouponController as CouponControllerAdmin;
use Modules\Coupon\Http\Controllers\API\User\CouponController as CouponControllerUser;

Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){

    Route::prefix('coupons')->group(function(){
            Route::get('/', [OrderController::class,'index'])->name('api.admin.coupons.index');    
            Route::get('/get-all-paginates', [CouponControllerAdmin::class,'getAllPaginates'])->name('api.admin.coupons.get-all-coupons-paginate');
                
            Route::get('trash', [CouponControllerAdmin::class,'trash'])->name('api.admin.coupons.trash');
            Route::get('restore-all', [CouponControllerAdmin::class,'restoreAll'])->name('api.admin.coupons.restore-all');
            Route::get('restore/{id}', [CouponControllerAdmin::class,'restore'])->name('api.admin.coupons.restore');
            Route::post('store', [CouponControllerAdmin::class,'store'])->name('api.admin.coupons.store');
            Route::get('show/{id}', [CouponControllerAdmin::class,'show'])->name('api.admin.coupons.show');
            Route::post('update/{id}', [CouponControllerAdmin::class,'update'])->name('api.admin.coupons.update');
            
            Route::get('destroy/{id}', [CouponControllerAdmin::class,'destroy'])->name('api.admin.coupons.destroy');        
            Route::get('force-delete/{id}', [CouponControllerAdmin::class,'forceDelete'])->name('api.admin.coupons.force-delete');
    });
});
    
/**************************Routes user***************************** */
Route::prefix('coupons')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::get('get-ended-coupons', [CouponControllerUser::class,'getEndedCoupons'])->name('api.coupons.get-ended-coupons');
    Route::get('get-coupons/{status}', [CouponControllerUser::class,'getCoupons'])->name('api.coupons.get-coupons');
    Route::post('store-coupon-order', [CouponControllerUser::class,'storeCouponOrder'])->name('api.coupons.store-coupon-order');
    Route::get('delete-coupon-order/{couponId}', [CouponControllerUser::class,'deleteCouponOrder'])->name('api.coupons.delete-coupon-order');

});
