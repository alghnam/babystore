<?php

use Illuminate\Http\Request;
use Modules\BuyingSystemMount\Http\Controllers\API\User\BuyingSystemMountController as BuyingSystemMountControllerUser;
 use Modules\BuyingSystemMount\Http\Controllers\API\Admin\BuyingSystemMountController as BuyingSystemMountControllerAdmin;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('buying-system-mounts')->group(function(){
                Route::get('/get-all-paginates', [BuyingSystemMountControllerAdmin::class,'getAllPaginates'])->name('api.admin.buying-system-mounts.paginate');

                Route::post('update/{id}', [BuyingSystemMountControllerAdmin::class,'update'])->name('api.admin.buying-system-mount.update');

    });
});

Route::prefix('buying-system-mounts')->middleware(['auth:api'])->group(function(){

    Route::get('get-buying-system-mount', [BuyingSystemMountControllerUser::class,'getBuyingSystemMount'])->name('api.buying-system-mount.get-buying-system-mount');
});
