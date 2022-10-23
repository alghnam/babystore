<?php

use Illuminate\Http\Request;
use Modules\Category\Http\Controllers\API\Admin\CategoryController as CategoryControllerAdmin;
use Modules\Category\Http\Controllers\API\Admin\SubCategoryController as SubCategoryControllerAdmin;
use Modules\Category\Http\Controllers\API\User\CategoryController as CategoryUserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    

    Route::prefix('categories')->group(function(){
        Route::get('/', [CategoryControllerAdmin::class,'index'])->name('api.admin.categories.index');    
        Route::get('/main-category-by-name/{subCategoryId}', [CategoryControllerAdmin::class,'mainCategoryByName'])->name('api.admin.categories.main-category-by-name');        
        Route::get('/get-main-categories', [CategoryControllerAdmin::class,'getMainCategories'])->name('api.admin.categories.get-main-categories');
        Route::get('/get-main-paginates', [CategoryControllerAdmin::class,'getAllPaginates'])->name('api.admin.categories.get-main-categories-paginate');
        Route::get('/get-sub-categories-for-main/{id}', [CategoryControllerAdmin::class,'getSubCategoriesForMain'])->name('api.admin.categories.get-sub-categories-for-main');        
        Route::get('/get-sub-categories', [CategoryControllerAdmin::class,'getSubCategories'])->name('api.admin.categories.get-sub-categories');
        Route::get('/get-first-sub-categories-paginate', [CategoryControllerAdmin::class,'getFirstSubCategoriesPaginate'])->name('api.admin.categories.get-first-sub-categories-paginate');
        Route::get('/get-all-paginates', [CategoryControllerAdmin::class,'getAllPaginates'])->name('api.admin.categories.get-all-categories-paginate');
        Route::get('trash', [CategoryControllerAdmin::class,'trash'])->name('api.admin.categories.trash');
        Route::get('trash-sub', [CategoryControllerAdmin::class,'trashSub'])->name('api.admin.categories.trash-sub');
        Route::get('restore-all', [CategoryControllerAdmin::class,'restoreAll'])->name('api.admin.categories.restore-all');
        Route::get('restore/{id}', [CategoryControllerAdmin::class,'restore'])->name('api.admin.categories.restore');
        Route::post('store', [CategoryControllerAdmin::class,'store'])->name('api.admin.categories.store');
        Route::get('show/{id}', [CategoryControllerAdmin::class,'show'])->name('api.admin.categories.show');
        Route::post('update/{id}', [CategoryControllerAdmin::class,'update'])->name('api.admin.categories.update');
        Route::get('destroy/{id}', [CategoryControllerAdmin::class,'destroy'])->name('api.admin.categories.destroy');        
        Route::get('force-delete/{id}', [CategoryControllerAdmin::class,'forceDelete'])->name('api.admin.categories.force-delete');
        
        
        Route::prefix('sub')->group(function(){
            Route::get('/', [SubCategoryControllerAdmin::class,'index'])->name('api.admin.categories.sub.get-sub-categories-paginate');
            Route::get('/get-all-paginates', [SubCategoryControllerAdmin::class,'getAllPaginates'])->name('api.admin.categories.sub.get-all-categories-paginate');
                   Route::get('/get-second-sub-categories-for-sub/{id}', [SubCategoryControllerAdmin::class,'getSecondSubCategoriesForSub'])->name('api.admin.categories.get-sub-categories-for-sub');        

            Route::get('trash', [SubCategoryControllerAdmin::class,'trash'])->name('api.admin.categories.sub.trash');
            Route::get('restore-all', [SubCategoryControllerAdmin::class,'restoreAll'])->name('api.admin.categories.sub.restore-all');
            Route::get('restore/{id}', [SubCategoryControllerAdmin::class,'restore'])->name('api.admin.categories.sub.restore');
            Route::post('store', [SubCategoryControllerAdmin::class,'store'])->name('api.admin.categories.sub.store');
            Route::get('show/{id}', [SubCategoryControllerAdmin::class,'show'])->name('api.admin.categories.sub.show');
            Route::post('update/{id}', [SubCategoryControllerAdmin::class,'update'])->name('api.admin.categories.sub.update');
            Route::get('destroy/{id}', [SubCategoryControllerAdmin::class,'destroy'])->name('api.admin.categories.sub.destroy');        
            Route::get('force-delete/{id}', [SubCategoryControllerAdmin::class,'forceDelete'])->name('api.admin.categories.sub.force-delete');
        });
        
    });
});


/************************Routes User*********************/

    Route::prefix('categories')->group(function(){
                Route::get('/get-main-categories', [CategoryUserController::class,'getMainCategoriesPaginate'])->name('api.admin.categories.get-main-categories-user-paginate');

        Route::get('/get-sub-categories-for-main/{id}', [CategoryUserController::class,'getSubCategoriesForMainCategoryPaginate'])->name('api.admin.categories.get-sub-categories-for-main-user-paginate');        
        Route::get('/get-sub-categories-for-sub/{category_id}', [CategoryUserController::class,'getSubCategoriesForSubCategoryPaginate'])->name('api.admin.categories.get-sub-categories-for-sub-user-paginate');        
        Route::get('/get-sub-categories', [CategoryUserController::class,'getSubCategories'])->name('api.admin.categories.get-sub-categories-for-user');
        Route::get('/get-second-sub-categories', [CategoryUserController::class,'getSecondSubCategories'])->name('api.admin.categories.get-second-sub-categories');

    });