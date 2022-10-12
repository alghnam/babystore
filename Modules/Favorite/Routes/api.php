<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Favorite\Http\Controllers\API\Admin\FavoriteController as FavoriteControllerAdmin;
use Modules\Favorite\Http\Controllers\API\User\FavoriteController as FavoriteControllerUser;

/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('favorites')->group(function(){
        Route::get('/', [FavoriteControllerAdmin::class,'index'])->name('api.admin.favorites.index');    
        Route::get('/get-all-paginates', [FavoriteControllerAdmin::class,'getAllPaginates'])->name('api.admin.favorites.get-all-favorites-paginate');        
        Route::get('/get-favorites-product/{productId}', [FavoriteControllerAdmin::class,'getfavoritesProduct'])->name('api.admin.get-favorites-product');
        Route::get('trash', [FavoriteControllerAdmin::class,'trash'])->name('api.admin.favorites.trash');
        Route::get('restore-all', [FavoriteControllerAdmin::class,'restoreAll'])->name('api.admin.favorites.restore-all');
        Route::get('restore/{id}', [FavoriteControllerAdmin::class,'restore'])->name('api.admin.favorites.restore');
        Route::post('store', [FavoriteControllerAdmin::class,'store'])->name('api.admin.favorites.store');
        Route::get('show/{id}', [FavoriteControllerAdmin::class,'show'])->name('api.admin.favorites.show');
        Route::post('update/{id}', [FavoriteControllerAdmin::class,'update'])->name('api.admin.favorites.update');
        Route::get('destroy/{id}', [FavoriteControllerAdmin::class,'destroy'])->name('api.admin.favorites.destroy');        
        Route::get('force-delete/{id}', [FavoriteControllerAdmin::class,'forceDelete'])->name('api.admin.favorites.force-delete');
    });
});
    Route::prefix('favorites')->middleware(['auth:api'])->group(function(){

        Route::post('add-to-favorite/{productId}', [FavoriteControllerUser::class,'addToFavorite'])->name('api.admin.favorites.add-to-favorite');
        Route::get('remove-from-favorite/{id}', [FavoriteControllerUser::class,'removeFromFavorite'])->name('api.admin.favorites.remove-from-favorite');
        Route::get('my-favorites', [FavoriteControllerUser::class,'myFavorites'])->name('api.admin.favorites.my-favorites');
});
