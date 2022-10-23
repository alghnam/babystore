<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\View\Http\Controllers\API\User\ViewController as ViewControllerUser;
use Modules\View\Http\Controllers\API\Admin\ViewController as ViewControllerAdmin;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('views')->group(function(){
        Route::get('/', [ViewControllerAdmin::class,'index'])->name('api.admin.views.index');    
        Route::get('/get-all-paginates', [ViewControllerAdmin::class,'getAllPaginates'])->name('api.admin.views.get-all-views-paginate');
        Route::get('/count-data', [ViewControllerAdmin::class,'countData'])->name('api.admin.views.count-data');
            
        Route::get('trash', [ViewControllerAdmin::class,'trash'])->name('api.admin.views.trash');
        Route::get('restore-all', [ViewControllerAdmin::class,'restoreAll'])->name('api.admin.views.restore-all');
        Route::get('restore/{id}', [ViewControllerAdmin::class,'restore'])->name('api.admin.views.restore');
        Route::post('store', [ViewControllerAdmin::class,'store'])->name('api.admin.views.store');
        Route::get('show/{id}', [ViewControllerAdmin::class,'show'])->name('api.admin.views.show');
        Route::post('update/{id}', [ViewControllerAdmin::class,'update'])->name('api.admin.views.update');
        
        Route::get('destroy/{id}', [ViewControllerAdmin::class,'destroy'])->name('api.admin.views.destroy');        
        Route::get('force-delete/{id}', [ViewControllerAdmin::class,'forceDelete'])->name('api.admin.views.force-delete');
    });
});

         Route::prefix('views')->middleware(['auth:api'])->group(function(){

            Route::post('add-to-view', [ViewControllerUser::class,'addToview'])->name('api.admin.views.add-to-view');
            Route::get('my-views', [ViewControllerUser::class,'myviews'])->name('api.admin.views.my-views');
    });
    