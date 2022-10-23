<?php

use Modules\Order\Http\Controllers\API\Admin\OrderController as OrderControllerAdmin;
use Modules\Order\Http\Controllers\API\User\OrderController as OrderControllerUser;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::get('/counts-all-data', [OrderControllerAdmin::class,'countsAllData'])->name('api.admin.counts-all-data');    
    Route::get('/show-currency', [OrderControllerAdmin::class,'showCurrency'])->name('api.admin.show-currency');    

    Route::prefix('orders')->group(function(){
        Route::get('/', [OrderControllerAdmin::class,'index'])->name('api.admin.orders.index');    
        Route::get('/count', [OrderControllerAdmin::class,'countData'])->name('api.admin.orders.count-data');    
        Route::get('/prices-sent-delivered-orders', [OrderControllerAdmin::class,'pricesSentDeliveredOrders'])->name('api.admin.orders.prices-sent-delivered');    
        Route::get('/sent-delivered', [OrderControllerAdmin::class,'sentDeliveredOrders'])->name('api.admin.orders.sent-delivered');    
        Route::get('/shipping', [OrderControllerAdmin::class,'shippingOrders'])->name('api.admin.orders.shipping');    
        Route::get('/get-orders-group-month', [OrderControllerAdmin::class,'getOrdersGroupMonth'])->name('api.admin.orders.get-orders-group-month');    
        Route::get('/get-all-paginates', [OrderControllerAdmin::class,'getAllPaginates'])->name('api.admin.orders.get-all-orders-paginate');
        Route::get('/latest', [OrderControllerAdmin::class,'getLatestOrders'])->name('api.admin.orders.get-latest-orders');
            
        Route::get('trash', [OrderControllerAdmin::class,'trash'])->name('api.admin.orders.trash');
        Route::get('restore-all', [OrderControllerAdmin::class,'restoreAll'])->name('api.admin.orders.restore-all');
        Route::get('restore/{id}', [OrderControllerAdmin::class,'restore'])->name('api.admin.orders.restore');
        Route::post('store', [OrderControllerAdmin::class,'store'])->name('api.admin.orders.store');
        Route::get('show/{id}', [OrderControllerAdmin::class,'show'])->name('api.admin.orders.show');
        Route::post('update/{id}', [OrderControllerAdmin::class,'update'])->name('api.admin.orders.update');
        
        Route::get('destroy/{id}', [OrderControllerAdmin::class,'destroy'])->name('api.admin.orders.destroy');        
        Route::get('force-delete/{id}', [OrderControllerAdmin::class,'forceDelete'])->name('api.admin.orders.force-delete');
    });
});

/**************************Routes user***************************** */
Route::post('payment-process', [OrderControllerUser::class,'paymentProcess'])->name('api.orders.payment-processs');
    Route::prefix('orders')->middleware(['auth:api'])->namespace('API')->group(function(){
      Route::get('show-data-user-address', [OrderControllerUser::class,'showDataUserAddress'])->name('api.admin.orders.show-data-user-address');
      Route::get('get-all-addresses-user', [OrderControllerUser::class,'getAllAddressesUser'])->name('api.admin.orders.get-all-addresses-user');
      Route::get('my-orders', [OrderControllerUser::class,'myOrders'])->name('api.admin.orders.my-orders');
      
      Route::get('my-orders/{status}', [OrderControllerUser::class,'myOrdersStatus'])->name('api.admin.orders.my-orders-status');

    //   Route::get('my-orders-being-processed', [OrderControllerUser::class,'myOrdersBeingProcessed'])->name('api.admin.orders.my-orders-being-processed');
    //   Route::get('my-orders-shipping', [OrderControllerUser::class,'myOrdersShipping'])->name('api.admin.orders.my-orders-shipping');
    //   Route::get('my-orders-sent-delivered-handed', [OrderControllerUser::class,'myOrdersSentDeliveredHanded'])->name('api.admin.orders.my-orders-sent-delivered-handed');
    //   Route::get('my-orders-hanging', [OrderControllerUserOrderControllerUser::class,'myOrdersHanging'])->name('api.admin.orders.my-orders-hanging');

      Route::get('view-my-order/{id}', [OrderControllerUser::class,'viewMyOrder'])->name('api.admin.orders.view-my-order');
      Route::post('add-review-order/{orderId}', [OrderControllerUser::class,'addReviewOrder'])->name('api.admin.orders.add-review-order');
      Route::post('view-review-order/{orderId}', [OrderControllerUser::class,'viewReviewOrder'])->name('api.admin.orders.view-review-order');
      
      
      
      Route::post('add-order', [OrderControllerUser::class,'addOrder'])->name('api.admin.orders.add-order');
   Route::post('add-address', [OrderControllerUser::class,'addAddress'])->name('api.admin.orders.add-address');
   Route::get('resend-code/{addressId}/{phone_no}', [OrderControllerUser::class,'resendCode'])->name('api.admin.orders.resend-code');
   Route::post('check-code-address', [OrderControllerUser::class,'checkCodeAddress'])->name('api.admin.orders.check-code-address');

   Route::get('show-address/{addressId}', [OrderControllerUser::class,'showAddress'])->name('api.admin.orders.show-address');
      Route::post('update-address/{addressId}', [OrderControllerUser::class,'updateAddress'])->name('api.admin.orders.update-address');
      Route::get('delete-address/{addressId}', [OrderControllerUser::class,'deleteAddress'])->name('api.admin.orders.delete-address');

     // Route::get('get-checkout-id/{price}', [OrderControllerUser::class,'getCheckoutId'])->name('api.admin.orders.get-checkout-id');
      Route::get('result-payment/true', [OrderControllerUser::class,'resultPayment'])->name('api.admin.orders.result-payment');

      Route::post('finishing-order', [OrderControllerUser::class,'finishingOrder'])->name('api.admin.orders.finishing-order');
    
      Route::post('re-finishing-order/{orderId}', [OrderControllerUser::class,'reFinishingOrder'])->name('api.admin.orders.re-finishing-order');
      Route::post('cancel-order/{orderId}', [OrderControllerUser::class,'cancelOrder'])->name('api.admin.orders.cancel-order');

      Route::post('get-payment-status/{id}', [OrderControllerUser::class,'getPaymentStatus'])->name('api.admin.orders.get-payment-status');
    //   Route::get('get-coupon-order/{orderId}', [OrderControllerUser::class,'getCouponOrder'])->name('api.admin.orders.get-coupon-order');
      
      Route::get('get-all-data-order', [OrderControllerUser::class,'getAllDataOrder'])->name('api.admin.orders.get-all-data-order');
      
      

    });
