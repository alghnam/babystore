<?php

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

use Modules\Geocode\Http\Controllers\WEB\CountryController;
use Modules\Geocode\Http\Controllers\WEB\CityController;
use Modules\Geocode\Http\Controllers\WEB\TownController;

Route::prefix('admin')->group(function() {

    Route::prefix('countries')->group(function(){
        Route::get('/', [CountryController::class,'index'])->name('admin.countries.index');
        Route::get('trash', [CountryController::class,'trash'])->name('admin.countries.trash');
        Route::get('restore-all', [CountryController::class,'restoreAll'])->name('admin.countries.restore-all');
        Route::get('restore/{id}', [CountryController::class,'restore'])->name('admin.countries.restore');
        Route::get('create', [CountryController::class,'create'])->name('admin.countries.create');
        Route::post('store', [CountryController::class,'store'])->name('admin.countries.store');
        Route::get('edit/{id}', [CountryController::class,'edit'])->name('admin.countries.edit');
        Route::get('show/{id}', [CountryController::class,'show'])->name('admin.countries.show');
        Route::post('update/{id}', [CountryController::class,'update'])->name('admin.countries.update');
        Route::get('destroy/{id}', [CountryController::class,'destroy'])->name('admin.countries.destroy');
        Route::get('force-delete/{id}', [CountryController::class,'forceDelete'])->name('admin.countries.force-delete');
    });

    
    Route::prefix('cities')->group(function(){
        Route::get('/', [CityController::class,'index'])->name('admin.cities.index');
        Route::get('trash', [CityController::class,'trash'])->name('admin.cities.trash');
        Route::get('cities-country/{countryId}', [CityController::class,'citiesCountry'])->name('admin.countries.cities-country');
        Route::get('restore-all', [CityController::class,'restoreAll'])->name('admin.cities.restore-all');
        Route::get('restore/{id}', [CityController::class,'restore'])->name('admin.cities.restore');
        Route::get('create', [CityController::class,'create'])->name('admin.cities.create');
        Route::post('store', [CityController::class,'store'])->name('admin.cities.store');
        Route::get('edit/{id}', [CityController::class,'edit'])->name('admin.cities.edit');
        Route::get('show/{id}', [CityController::class,'show'])->name('admin.cities.show');
        Route::post('update/{id}', [CityController::class,'update'])->name('admin.cities.update');
        Route::get('destroy/{id}', [CityController::class,'destroy'])->name('admin.cities.destroy');
        Route::get('force-delete/{id}', [CityController::class,'forceDelete'])->name('admin.cities.force-delete');
    });
    Route::prefix('towns')->group(function(){
        Route::get('/', [TownController::class,'index'])->name('admin.towns.index');
        Route::get('trash', [TownController::class,'trash'])->name('admin.towns.trash');
        Route::get('towns-city/{cityId}', [TownController::class,'townsCity'])->name('admin.countries.towns-city');
        Route::get('restore-all', [TownController::class,'restoreAll'])->name('admin.towns.restore-all');
        Route::get('restore/{id}', [TownController::class,'restore'])->name('admin.towns.restore');
        Route::get('create', [TownController::class,'create'])->name('admin.towns.create');
        Route::post('store', [TownController::class,'store'])->name('admin.towns.store');
        Route::get('edit/{id}', [TownController::class,'edit'])->name('admin.towns.edit');
        Route::get('show/{id}', [TownController::class,'show'])->name('admin.towns.show');
        Route::post('update/{id}', [TownController::class,'update'])->name('admin.towns.update');
        Route::get('destroy/{id}', [TownController::class,'destroy'])->name('admin.towns.destroy');
        Route::get('force-delete/{id}', [TownController::class,'forceDelete'])->name('admin.towns.force-delete');
    });

});
