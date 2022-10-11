<?php

use Illuminate\Http\Request;

use Modules\Rule\Http\Controllers\API\User\RuleController as RuleControllerUser;
use Modules\Rule\Http\Controllers\API\Admin\RuleController as RuleControllerAdmin;

/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('rules')->group(function(){
        Route::get('/', [RuleControllerAdmin::class,'index'])->name('api.admin.rules.index');    
        Route::get('/get-all-paginates', [RuleControllerAdmin::class,'getAllPaginates'])->name('api.admin.rules.get-all-rules-paginate');
            
        Route::get('trash', [RuleControllerAdmin::class,'trash'])->name('api.admin.rules.trash');
        Route::get('restore-all', [RuleControllerAdmin::class,'restoreAll'])->name('api.admin.rules.restore-all');
        Route::get('restore/{id}', [RuleControllerAdmin::class,'restore'])->name('api.admin.rules.restore');
        Route::post('store', [RuleControllerAdmin::class,'store'])->name('api.admin.rules.store');
        Route::get('show/{id}', [RuleControllerAdmin::class,'show'])->name('api.admin.rules.show');
        Route::post('update/{id}', [RuleControllerAdmin::class,'update'])->name('api.admin.rules.update');
        
        Route::get('destroy/{id}', [RuleControllerAdmin::class,'destroy'])->name('api.admin.rules.destroy');        
        Route::get('force-delete/{id}', [RuleControllerAdmin::class,'forceDelete'])->name('api.admin.rules.force-delete');
    });
});

//for user
Route::prefix('rules')->group(function(){
            Route::get('/show-rule/{id}', [RuleControllerUser::class,'showRule'])->name('api.products.show-rule');  
});
