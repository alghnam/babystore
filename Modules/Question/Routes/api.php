<?php

use Illuminate\Support\Facades\Route;
use Modules\Question\Http\Controllers\API\Admin\QuestionController as QuestionControllerAdmin;
use Modules\Question\Http\Controllers\API\Admin\Categories\QuestionController as QuestionCategoryControllerAdmin;
use Modules\Question\Http\Controllers\API\User\QuestionController as QuestionControllerUser;
use Modules\Question\Http\Controllers\API\User\Categories\QuestionController as QuestionCategoryControllerUser;

/**************************Routes Admin***************************** */
Route::namespace('API')->group(function(){
    Route::prefix('admin')->middleware(['auth:api'])->group(function(){
        Route::prefix('questions')->group(function(){
            Route::get('/', [QuestionControllerAdmin::class,'index'])->name('api.admin.questions.index');    
            Route::get('/get-all-paginates', [QuestionControllerAdmin::class,'getAllPaginates'])->name('api.admin.questions.get-all-questions-paginate');        
            Route::get('trash', [QuestionControllerAdmin::class,'trash'])->name('api.admin.questions.trash');
            Route::get('restore-all', [QuestionControllerAdmin::class,'restoreAll'])->name('api.admin.questions.restore-all');
            Route::get('restore/{id}', [QuestionControllerAdmin::class,'restore'])->name('api.admin.questions.restore');
            Route::post('store', [QuestionControllerAdmin::class,'store'])->name('api.admin.questions.store');
            Route::post('store-trans/{id}/{lang}', [QuestionControllerAdmin::class,'storeTrans'])->name('api.admin.questions.store-trans');
            Route::get('show/{id}', [QuestionControllerAdmin::class,'show'])->name('api.admin.questions.show');
            Route::post('update/{id}', [QuestionControllerAdmin::class,'update'])->name('api.admin.questions.update');
            Route::get('destroy/{id}', [QuestionControllerAdmin::class,'destroy'])->name('api.admin.questions.destroy');        
            Route::get('force-delete/{id}', [QuestionControllerAdmin::class,'forceDelete'])->name('api.admin.questions.force-delete');
    
        });
        
        Route::prefix('question-categories')->group(function(){
            Route::get('/', [QuestionCategoryControllerAdmin::class,'index'])->name('api.admin.question-categories.index');    
            Route::get('/get-all-paginates', [QuestionCategoryControllerAdmin::class,'getAllPaginates'])->name('api.admin.question-categories.get-all-questions-paginate');        
            Route::get('trash', [QuestionCategoryControllerAdmin::class,'trash'])->name('api.admin.question-categories.trash');
            Route::get('restore-all', [QuestionCategoryControllerAdmin::class,'restoreAll'])->name('api.admin.question-categories.restore-all');
            Route::get('restore/{id}', [QuestionCategoryControllerAdmin::class,'restore'])->name('api.admin.question-categories.restore');
            Route::post('store', [QuestionCategoryControllerAdmin::class,'store'])->name('api.admin.question-categories.store');
            Route::post('store-trans/{id}/{lang}', [QuestionCategoryControllerAdmin::class,'storeTrans'])->name('api.admin.question-categories.store-trans');
            Route::get('show/{id}', [QuestionCategoryControllerAdmin::class,'show'])->name('api.admin.question-categories.show');
            Route::post('update/{id}', [QuestionCategoryControllerAdmin::class,'update'])->name('api.admin.question-categories.update');
            Route::get('destroy/{id}', [QuestionCategoryControllerAdmin::class,'destroy'])->name('api.admin.question-categories.destroy');        
            Route::get('force-delete/{id}', [QuestionCategoryControllerAdmin::class,'forceDelete'])->name('api.admin.question-categories.force-delete');
    
        });
    
     });
     
    
     Route::prefix('questions')->group(function(){
             Route::get('/get-all-paginates', [QuestionControllerUser::class,'getAllPaginates'])->name('api.user.questions.get-all-questions-paginate');       
             Route::get('/get-all-questions-category-paginates/{categoryId}', [QuestionControllerUser::class,'getAllQuestionsCategoryPaginates'])->name('api.user.questions.get-all-questions-category-paginates');        
     });
       Route::prefix('question-categories')->group(function(){
             Route::get('/get-all-paginates', [QuestionCategoryControllerUser::class,'getAllPaginates'])->name('api.user.questions.get-all-questions-categories-paginate');       
     });
    
});