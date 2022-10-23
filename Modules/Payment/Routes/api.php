<?php

use Illuminate\Http\Request;

use Modules\Payment\Http\Controllers\API\Admin\PaymentController as PaymentControllerAdmin;
use Modules\Payment\Http\Controllers\API\User\PaymentController as PaymentControllerUser;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('payments')->group(function(){
        Route::get('/', [PaymentControllerAdmin::class,'index'])->name('api.admin.payments.index');    
        Route::get('/get-all-paginates', [PaymentControllerAdmin::class,'getAllPaginates'])->name('api.admin.carts.get-all-payments-paginate');
            
        Route::get('trash', [PaymentControllerAdmin::class,'trash'])->name('api.admin.payments.trash');
        Route::get('restore-all', [PaymentControllerAdmin::class,'restoreAll'])->name('api.admin.payments.restore-all');
        Route::get('restore/{id}', [PaymentControllerAdmin::class,'restore'])->name('api.admin.payments.restore');
        Route::post('store', [PaymentControllerAdmin::class,'store'])->name('api.admin.payments.store');
        Route::get('show/{id}', [PaymentControllerAdmin::class,'show'])->name('api.admin.payments.show');
        Route::post('update/{id}', [PaymentControllerAdmin::class,'update'])->name('api.admin.payments.update');
        
        Route::get('destroy/{id}', [PaymentControllerAdmin::class,'destroy'])->name('api.admin.payments.destroy');        
        Route::get('force-delete/{id}', [PaymentControllerAdmin::class,'forceDelete'])->name('api.admin.payments.force-delete');
    });
});
Route::prefix('payments')->middleware(['auth:api'])->group(function(){
           Route::get('/get-all-public-payments', [PaymentControllerUser::class,'getAllPublicPayments'])->name('api.reviews.get-all-public-payments');
           Route::get('/get-all-private-payments', [PaymentControllerUser::class,'getAllPrivatePayments'])->name('api.reviews.get-all-private-payments');
           Route::get('/get-all-payments', [PaymentControllerUser::class,'getAllPublicPrivatePayments'])->name('api.reviews.get-all-payments');
           
           
 
});
