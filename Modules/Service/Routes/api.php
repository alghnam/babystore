<?php

use Illuminate\Http\Request;
use Modules\Service\Http\Controllers\API\User\ServiceController as ServiceControllerUser;
use Modules\Service\Http\Controllers\API\Admin\ServiceController as ServiceControllerAdmin;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('services')->group(function(){
        Route::get('/', [ServiceControllerAdmin::class,'index'])->name('api.admin.services.index');    
        Route::get('/get-all-paginates', [ServiceControllerAdmin::class,'getAllPaginates'])->name('api.admin.services.get-all-services-paginate');
            
        Route::get('trash', [ServiceControllerAdmin::class,'trash'])->name('api.admin.services.trash');
        Route::get('restore-all', [ServiceControllerAdmin::class,'restoreAll'])->name('api.admin.services.restore-all');
        Route::get('restore/{id}', [ServiceControllerAdmin::class,'restore'])->name('api.admin.services.restore');
        Route::post('store', [ServiceControllerAdmin::class,'store'])->name('api.admin.services.store');
        Route::get('show/{id}', [ServiceControllerAdmin::class,'show'])->name('api.admin.services.show');
        Route::post('update/{id}', [ServiceControllerAdmin::class,'update'])->name('api.admin.services.update');
        
        Route::get('destroy/{id}', [ServiceControllerAdmin::class,'destroy'])->name('api.admin.services.destroy');        
        Route::get('force-delete/{id}', [ServiceControllerAdmin::class,'forceDelete'])->name('api.admin.services.force-delete');
    });
});

/**************************Routes user***************************** */
Route::prefix('services')->middleware(['auth:api'])->namespace('API')->group(function(){
      Route::get('get-services', function(){
          dd(6);
          })->name('api.orders.get-services');
      Route::get('show-service/{id}', [ServiceControllerUser::class,'showService'])->name('api.orders.show-service');
});