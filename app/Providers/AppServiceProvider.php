<?php

namespace App\Providers;


use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
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

        \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
            Session::push('queries', ['query' => $query->sql, 'Time' => $query->time]);
            Session::put('time', Session::get('time') + $query->time);
        });

        Schema::defaultStringLength(191);
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
