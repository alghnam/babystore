<?php

use Illuminate\Http\Request;

use Modules\Geocode\Http\Controllers\API\User\CountryController as CountryControllerUser;
use Modules\Geocode\Http\Controllers\API\User\CityController as CityControllerUser;
use Modules\Geocode\Http\Controllers\API\User\TownController as TownControllerUser;
use Modules\Geocode\Http\Controllers\API\Admin\CountryController as CountryControllerAdmin;
use Modules\Geocode\Http\Controllers\API\Admin\CityController as CityControllerAdmin;
use Modules\Geocode\Http\Controllers\API\Admin\TownController as TownControllerAdmin;
/**************************Routes Admin***************************** */
Route::prefix('admin')->middleware(['auth:api'])->namespace('API')->group(function(){
    Route::prefix('countries')->group(function(){
        Route::get('/', [CountryControllerAdmin::class,'index'])->name('api.admin.countries.index');    
        Route::get('/get-all-paginates', [CountryControllerAdmin::class,'getAllPaginates'])->name('api.admin.countries.get-all-countries-paginate');
            
        Route::get('trash', [CountryControllerAdmin::class,'trash'])->name('api.admin.countries.trash');
        Route::get('restore-all', [CountryControllerAdmin::class,'restoreAll'])->name('api.admin.countries.restore-all');
        Route::get('restore/{id}', [CountryControllerAdmin::class,'restore'])->name('api.admin.countries.restore');
        Route::post('store', [CountryControllerAdmin::class,'store'])->name('api.admin.countries.store');
        Route::get('show/{id}', [CountryControllerAdmin::class,'show'])->name('api.admin.countries.show');
        Route::post('update/{id}', [CountryControllerAdmin::class,'update'])->name('api.admin.countries.update');
        
        Route::get('destroy/{id}', [CountryControllerAdmin::class,'destroy'])->name('api.admin.countries.destroy');        
        Route::get('force-delete/{id}', [CountryControllerAdmin::class,'forceDelete'])->name('api.admin.countries.force-delete');
    });
    /**************************Routes cities***************************** */
    Route::prefix('cities')->group(function(){
        Route::get('/', [CityControllerAdmin::class,'index'])->name('api.admin.cities.index');    
        Route::get('/get-all-paginates', [CityControllerAdmin::class,'getAllPaginates'])->name('api.admin.cities.get-all-cities-paginate');
            
        Route::get('trash', [CityControllerAdmin::class,'trash'])->name('api.admin.cities.trash');
        Route::get('restore-all', [CityControllerAdmin::class,'restoreAll'])->name('api.admin.cities.restore-all');
        Route::get('restore/{id}', [CityControllerAdmin::class,'restore'])->name('api.admin.cities.restore');
        Route::post('store', [CityControllerAdmin::class,'store'])->name('api.admin.cities.store');
        Route::get('show/{id}', [CityControllerAdmin::class,'show'])->name('api.admin.cities.show');
        Route::post('update/{id}', [CityControllerAdmin::class,'update'])->name('api.admin.cities.update');
        
        Route::get('destroy/{id}', [CityControllerAdmin::class,'destroy'])->name('api.admin.cities.destroy');        
        Route::get('force-delete/{id}', [CityControllerAdmin::class,'forceDelete'])->name('api.admin.cities.force-delete');
    });    
    /**************************Routes towns***************************** */
    Route::prefix('towns')->group(function(){
        Route::get('/', [TownControllerAdmin::class,'index'])->name('api.admin.towns.index');    
        Route::get('/get-all-paginates', [TownControllerAdmin::class,'getAllPaginates'])->name('api.admin.towns.get-all-towns-paginate');
            
        Route::get('trash', [TownControllerAdmin::class,'trash'])->name('api.admin.towns.trash');
        Route::get('restore-all', [TownControllerAdmin::class,'restoreAll'])->name('api.admin.towns.restore-all');
        Route::get('restore/{id}', [TownControllerAdmin::class,'restore'])->name('api.admin.towns.restore');
        Route::post('store', [TownControllerAdmin::class,'store'])->name('api.admin.towns.store');
        Route::get('show/{id}', [TownControllerAdmin::class,'show'])->name('api.admin.towns.show');
        Route::post('update/{id}', [TownControllerAdmin::class,'update'])->name('api.admin.towns.update');
        
        Route::get('destroy/{id}', [TownControllerAdmin::class,'destroy'])->name('api.admin.towns.destroy');        
        Route::get('force-delete/{id}', [TownControllerAdmin::class,'forceDelete'])->name('api.admin.towns.force-delete');
    });
});




Route::prefix('geocodes')->namespace('API')->group(function(){
        Route::get('/get-all-countries-for-user', [CountryControllerUser::class,'getAllCountries'])->name('api.user.geocodes.get-all-countries-for-user');
        Route::get('/get-all-cities-country-for-user/{countryId}', [CityControllerUser::class,'getAllCitiesCountry'])->name('api.user.geocodes.get-all-cities-country-for-user');
        Route::get('/get-all-towns-cities-for-user/{cityId}', [TownControllerUser::class,'getAllTownsCity'])->name('api.admin.user.get-all-towns-cities-for-user');
});