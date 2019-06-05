<?php

namespace App\Providers;

use App\ConnectionPool;
use Illuminate\Support\ServiceProvider;
use React\EventLoop\Factory;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->instance('react.loop', Factory::create());
        $this->app->instance('connection.pool', new ConnectionPool());
//        $this->app->instance('psr.factory', function(){
//            $psr17Factory = new Psr17Factory();
//            return new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
//        });
    }
}
