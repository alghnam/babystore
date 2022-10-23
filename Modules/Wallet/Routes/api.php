<?php

use Illuminate\Http\Request;
use Modules\Wallet\Http\Controllers\API\User\WalletController as WalletControllerUser;
use Modules\Wallet\Http\Controllers\API\Admin\WalletController as WalletControllerAdmin;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('wallets')->group(function(){
        Route::get('/', [WalletControllerAdmin::class,'index'])->name('api.admin.wallets.index');    
        Route::get('/get-all-paginates', [WalletControllerAdmin::class,'getAllPaginates'])->name('api.admin.wallets.get-all-wallets-paginate');
            
        Route::get('trash', [WalletControllerAdmin::class,'trash'])->name('api.admin.wallets.trash');
        Route::get('restore-all', [WalletControllerAdmin::class,'restoreAll'])->name('api.admin.wallets.restore-all');
        Route::get('restore/{id}', [WalletControllerAdmin::class,'restore'])->name('api.admin.wallets.restore');
        Route::post('store', [WalletControllerAdmin::class,'store'])->name('api.admin.wallets.store');
        Route::get('show/{id}', [WalletControllerAdmin::class,'show'])->name('api.admin.wallets.show');
        Route::post('update/{id}', [WalletControllerAdmin::class,'update'])->name('api.admin.wallets.update');
        
        Route::get('destroy/{id}', [WalletControllerAdmin::class,'destroy'])->name('api.admin.wallets.destroy');        
        Route::get('force-delete/{id}', [WalletControllerAdmin::class,'forceDelete'])->name('api.admin.wallets.force-delete');
    });
});

Route::prefix('wallets')->middleware(['auth:api'])->group(function(){

    Route::post('make-replacing-points/{points}', [WalletControllerUser::class,'makeReplacingPoints'])->name('api.admin.wallets.make-replacing-points');
    Route::get('points-wallet', [WalletControllerUser::class,'pointsWallet'])->name('api.admin.wallets.points-wallet');

    Route::get('count-data', [WalletControllerUser::class,'countData'])->name('api.admin.wallets.count-data');

    Route::get('balance-wallet', [WalletControllerUser::class,'balanceWallet'])->name('api.admin.wallets.balance-wallet');
    Route::post('add-to-wallet', [WalletControllerUser::class,'addTowallet'])->name('api.admin.wallets.add-to-wallet');
    


    
});
