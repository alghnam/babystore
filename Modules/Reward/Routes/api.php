<?php

use Illuminate\Http\Request;
use Modules\Reward\Http\Controllers\API\Admin\RewardController as RewardControllerAdmin;
use Modules\Reward\Http\Controllers\API\User\RewardController as RewardControllerUser;

/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('rewards')->group(function(){
        Route::get('/', [RewardControllerAdmin::class,'index'])->name('api.admin.rewards.index');    
        Route::get('/get-all-paginates', [RewardControllerAdmin::class,'getAllPaginates'])->name('api.admin.rewards.get-all-rewards-paginate');
            
        Route::get('trash', [RewardControllerAdmin::class,'trash'])->name('api.admin.rewards.trash');
        Route::get('restore-all', [RewardControllerAdmin::class,'restoreAll'])->name('api.admin.rewards.restore-all');
        Route::get('restore/{id}', [RewardControllerAdmin::class,'restore'])->name('api.admin.rewards.restore');
        Route::post('store', [RewardControllerAdmin::class,'store'])->name('api.admin.rewards.store');
        Route::get('show/{id}', [RewardControllerAdmin::class,'show'])->name('api.admin.rewards.show');
        Route::post('update/{id}', [RewardControllerAdmin::class,'update'])->name('api.admin.rewards.update');
        
        Route::get('destroy/{id}', [RewardControllerAdmin::class,'destroy'])->name('api.admin.rewards.destroy');        
        Route::get('force-delete/{id}', [RewardControllerAdmin::class,'forceDelete'])->name('api.admin.rewards.force-delete');
    });
});

  Route::prefix('rewards')->middleware(['auth:api'])->group(function(){
                Route::get('get-rewards/{status}', [RewardControllerUser::class,'getRewards'])->name('api.rewards.get-rewards');

    });