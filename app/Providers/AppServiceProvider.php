<?php

namespace App\Providers;

use View;
use App\Models\SOP_Setup;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $data=SOP_Setup::all();
        View::share('data',$data);

        $user = User::all();
		View::share('user', $user);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
