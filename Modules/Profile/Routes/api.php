<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Profile\Http\Controllers\API\User\ProfileController;

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


Route::prefix('profile')->middleware(['auth:api'])->group(function(){
    Route::prefix('admin')->namespace('API')->group(function(){
        Route::get('accepting-on-request-documentation/{userId}', [ProfileController::class,'acceptingOnRequestDocumentation'])->name('api.profile.accepting-on-request-documentation');

    });
    // Route::get('show/{userId}', [ProfileController::class,'show'])->name('api.profile.show');
    Route::get('show', [ProfileController::class,'show'])->name('api.profile.show');
    Route::post('update', [ProfileController::class,'update'])->name('api.profile.update');
    Route::post('update-password', [ProfileController::class,'updatePassword'])->name('api.profile.update-password');
    Route::get('request-documentation/{userId}', function(){
        dd(0);
    })->name('api.profile.request-documentation');

});