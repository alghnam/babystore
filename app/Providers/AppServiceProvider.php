<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */

    public function register()
    {
      // Session::put('applocale','en');
        // $session_id=Str::random(30);
        //  Storage::put('session_id',null);
        // Storage::put('session_id',null);
        // Storage::put('userId',null);
        // Storage::put('step1',null);
        // Storage::put('step2',null);
        // Storage::put('code',null);
        // Storage::put('confirmed',null);
        // Storage::put('sentCode',null);
        // Storage::put('finished',null);
        // Storage::put('sentCode',null);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // Using view composer to set following variables globally
        view()->composer('*',function($view) {
            // $view->with('user', Auth::user());
                view()->share('success', 'تمت العملية بنجاح ');
                view()->share('error', 'حدث خطأ ما');
        });
    }
}
