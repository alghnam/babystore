<?php

use Illuminate\Support\Facades\App;
use Modules\Auth\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\WEB\UserController;
use Modules\Auth\Http\Controllers\WEB\RoleController;
use Modules\Auth\Http\Controllers\WEB\PermissionController;
use Modules\Geocode\Http\Controllers\WEB\CountryController;
use Modules\Geocode\Http\Controllers\WEB\CityController;
use Modules\Geocode\Http\Controllers\WEB\TownController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**************************Routes Admin***************************** */
Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::prefix('users')->group(function(){
        Route::get('/', [UserController::class,'index'])->name('admin.users.index');
        Route::get('trash', [UserController::class,'trash'])->name('admin.users.trash');
        Route::get('restore-all', [UserController::class,'restoreAll'])->name('admin.users.restore-all');
        Route::get('restore/{id}', [UserController::class,'restore'])->name('admin.users.restore');
        Route::get('create', [UserController::class,'create'])->name('admin.users.create');
        Route::post('store', [UserController::class,'store'])->name('admin.users.store');
        Route::get('edit/{id}', [UserController::class,'edit'])->name('admin.users.edit');
        Route::get('show/{id}', [UserController::class,'show'])->name('admin.users.show');
        Route::post('update/{id}', [UserController::class,'update'])->name('admin.users.update');
        Route::get('destroy/{id}', [UserController::class,'destroy'])->name('admin.users.destroy');
        Route::get('force-delete/{id}', [UserController::class,'forceDelete'])->name('admin.users.force-delete');
    });
    Route::prefix('roles')->group(function(){
        Route::get('/', [RoleController::class,'index'])->name('admin.roles.index');
        Route::get('trash', [RoleController::class,'trash'])->name('admin.roles.trash');
        Route::get('restore-all', [RoleController::class,'restoreAll'])->name('admin.roles.restore-all');
        Route::get('restore/{id}', [RoleController::class,'restore'])->name('admin.roles.restore');
        Route::get('create', [RoleController::class,'create'])->name('admin.roles.create');
        Route::post('store', [RoleController::class,'store'])->name('admin.roles.store');
        Route::get('edit/{id}', [RoleController::class,'edit'])->name('admin.roles.edit');
        Route::get('show/{id}', [RoleController::class,'show'])->name('admin.roles.show');
        Route::post('update/{id}', [RoleController::class,'update'])->name('admin.roles.update');
        Route::get('destroy/{id}', [RoleController::class,'destroy'])->name('admin.roles.destroy');
        Route::get('force-delete/{id}', [RoleController::class,'forceDelete'])->name('admin.roles.force-delete');
    });
    Route::prefix('permissions')->group(function(){
        Route::get('/', [PermissionController::class,'index'])->name('admin.permissions.index');
        Route::get('trash', [PermissionController::class,'trash'])->name('admin.permissions.trash');
        Route::get('restore-all', [PermissionController::class,'restoreAll'])->name('admin.permissions.restore-all');
        Route::get('restore/{id}', [PermissionController::class,'restore'])->name('admin.permissions.restore');
        Route::get('create', [PermissionController::class,'create'])->name('admin.permissions.create');
        Route::post('store', [PermissionController::class,'store'])->name('admin.permissions.store');
        Route::get('edit/{id}', [PermissionController::class,'edit'])->name('admin.permissions.edit');
        Route::get('show/{id}', [PermissionController::class,'show'])->name('admin.permissions.show');
        Route::post('update/{id}', [PermissionController::class,'update'])->name('admin.permissions.update');
        Route::get('destroy/{id}', [PermissionController::class,'destroy'])->name('admin.permissions.destroy');
        Route::get('force-delete/{id}', [PermissionController::class,'forceDelete'])->name('admin.permissions.force-delete');
    });

});

