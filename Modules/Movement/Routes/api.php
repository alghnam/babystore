<?php

use Illuminate\Http\Request;
use Modules\Movement\Http\Controllers\API\Admin\MovementController as MovementControllerAdmin;
use Modules\Movement\Http\Controllers\API\User\MovementController as MovementControllerUser;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('movements')->group(function(){
        Route::get('/', [MovementControllerAdmin::class,'index'])->name('api.admin.movements.index');    
        Route::get('/get-all-paginates', [MovementControllerAdmin::class,'getAllPaginates'])->name('api.admin.movements.get-all-movements-paginate');
            
        Route::get('trash', [MovementControllerAdmin::class,'trash'])->name('api.admin.movements.trash');
        Route::get('restore-all', [MovementControllerAdmin::class,'restoreAll'])->name('api.admin.movements.restore-all');
        Route::get('restore/{id}', [MovementControllerAdmin::class,'restore'])->name('api.admin.movements.restore');
        Route::post('store', [MovementControllerAdmin::class,'store'])->name('api.admin.movements.store');
        Route::get('show/{id}', [MovementControllerAdmin::class,'show'])->name('api.admin.movements.show');
        Route::post('update/{id}', [MovementControllerAdmin::class,'update'])->name('api.admin.movements.update');
        
        Route::get('destroy/{id}', [MovementControllerAdmin::class,'destroy'])->name('api.admin.movements.destroy');        
        Route::get('force-delete/{id}', [MovementControllerAdmin::class,'forceDelete'])->name('api.admin.movements.force-delete');
    });
});

Route::prefix('movements')->middleware(['auth:api'])->group(function(){

    Route::post('add-replaced-points', [MovementControllerUser::class,'addReplacedPoints'])->name('api.wallets.add-replaced-points');
    Route::get('movements-wallet-user', [MovementControllerUser::class,'getAllMovementsWalletUser'])->name('api.wallets.movements-wallet-wallet');
    Route::get('movements-wallet-points-user', [MovementControllerUser::class,'getAllMovementsWalletPointsUser'])->name('api.wallets.movements-wallet-points-wallet');
    Route::get('delete-movement', [MovementControllerUser::class,'deleteMovement'])->name('api.wallets.delete-movement');
});
