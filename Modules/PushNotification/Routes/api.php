<?php

use Illuminate\Http\Request;
use Modules\PushNotification\Http\Controllers\API\Admin\PushNotificationController as PushNotificationControllerAdmin;
use Modules\PushNotification\Http\Controllers\API\User\PushNotificationController as PushNotificationControllerUser;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('pushnotifications')->group(function(){
        Route::get('/', [PushNotificationControllerAdmin::class,'index'])->name('api.admin.pushnotifications.index');    
        Route::get('/get-all-paginates', [PushNotificationControllerAdmin::class,'getAllPaginates'])->name('api.admin.pushnotifications.get-all-pushnotifications-paginate');

        Route::get('/get-all-users-pushnotification-paginates/{id}', [PushNotificationControllerAdmin::class,'getAllUsersPushNotificationPaginates'])->name('api.admin.pushnotifications.get-all-users-pushnotification-paginates');
            
        Route::get('trash', [PushNotificationControllerAdmin::class,'trash'])->name('api.admin.pushnotifications.trash');
        Route::get('restore-all', [PushNotificationControllerAdmin::class,'restoreAll'])->name('api.admin.pushnotifications.restore-all');
        Route::get('restore/{id}', [PushNotificationControllerAdmin::class,'restore'])->name('api.admin.pushnotifications.restore');
        Route::post('store', [PushNotificationControllerAdmin::class,'store'])->name('api.admin.pushnotifications.store');
        Route::get('show/{id}', [PushNotificationControllerAdmin::class,'show'])->name('api.admin.pushnotifications.show');
        Route::post('update/{id}', [PushNotificationControllerAdmin::class,'update'])->name('api.admin.pushnotifications.update');
        
        Route::get('destroy/{id}', [PushNotificationControllerAdmin::class,'destroy'])->name('api.admin.pushnotifications.destroy');        
        Route::get('force-delete/{id}', [PushNotificationControllerAdmin::class,'forceDelete'])->name('api.admin.pushnotifications.force-delete');
    });
});

 Route::prefix('pushnotifications')->group(function(){
        Route::get('/get-notifications-for-user', [PushNotificationControllerUser::class,'getNotificationsForUser'])->name('api.pushnotifications.get-notifications-for-user');    
        Route::get('/get-latest-notification-for-user', [PushNotificationControllerUser::class,'getLatestNotificationForUser'])->name('api.pushnotifications.get-latest-notification-for-user');

    });
