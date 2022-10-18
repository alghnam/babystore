<?php

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
/**************************Auth************************************* */

use App\Http\Controllers\API\Auth\ForgotPasswordController;
use App\Http\Controllers\API\Auth\CheckCodeController;
use App\Http\Controllers\API\Auth\ResetPasswordController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// define("success", "تمت العملية بنجاح");
Route::get('alaa',function(){
   dd(7);
});
Route::get('/auth', [RegisterController::class, 'authLogin'])
                ->middleware('guest')
                ->name('auth-login');
//process : reg, login
Route::post('/register', [RegisterController::class, 'register'])
                ->middleware('guest');
Route::post('check-code-register/{rand}', [RegisterController::class,'checkCodeRegister'])->name('api.check-code-register');
Route::post('resend-code-register/{rand}', [RegisterController::class,'resendCodeRegister'])->name('api.resend-code-register');

Route::post('/login', [LoginController::class, 'login'])
                ->middleware('guest');
//opertaion reset pass
Route::post('password/forgot',  ForgotPasswordController::class);//to send  entered into phone_no entered
Route::post('password/code/check/{rand}', CheckCodeController::class);////to check code entered (that sent into email) with the code that it  sent into email
Route::post('password/reset/{rand}', ResetPasswordController::class);// to make reset pass this user through password entered now

//logout
Route::middleware(['auth:api'])->group(function(){
    Route::get('/logout', [LoginController::class, 'destroy'])
                    ->name('api-llogout');
});
