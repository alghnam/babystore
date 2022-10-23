<?php

use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Frontend\HomeController;



Auth::routes();

Route::get('alaa',function(){
    dd('alaa');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);
