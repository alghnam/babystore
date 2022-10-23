<?php

use Illuminate\Http\Request;
use Modules\Search\Http\Controllers\API\Admin\SearchController as SearchControllerAdmin;
use Modules\Search\Http\Controllers\API\User\SearchController as SearchControllerUser;

/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('searches')->group(function(){
        Route::get('/', [SearchControllerAdmin::class,'index'])->name('api.admin.searches.index');    
        Route::get('/get-all-paginates', [SearchControllerAdmin::class,'getAllPaginates'])->name('api.admin.searches.get-all-searches-paginate');
            
        Route::get('trash', [SearchControllerAdmin::class,'trash'])->name('api.admin.searches.trash');
        Route::get('restore-all', [SearchControllerAdmin::class,'restoreAll'])->name('api.admin.searches.restore-all');
        Route::get('restore/{id}', [SearchControllerAdmin::class,'restore'])->name('api.admin.searches.restore');
        Route::post('store', [SearchControllerAdmin::class,'store'])->name('api.admin.searches.store');
        Route::get('show/{id}', [SearchControllerAdmin::class,'show'])->name('api.admin.searches.show');
        Route::post('update/{id}', [SearchControllerAdmin::class,'update'])->name('api.admin.searches.update');
        
        Route::get('destroy/{id}', [SearchControllerAdmin::class,'destroy'])->name('api.admin.searches.destroy');        
        Route::get('force-delete/{id}', [SearchControllerAdmin::class,'forceDelete'])->name('api.admin.searches.force-delete');
    });
});


Route::prefix('search')->group(function(){

Route::get('latest-results-search',[SearchControllerUser::class,'latestResultsSearch']);
Route::get('delete-result-search/{id}', [SearchControllerUser::class,'deleteResultSearchUser']);
Route::get('delete-all-results-search-user', [SearchControllerUser::class,'deleteAllResultSearchUser']);

    
});