<?php

namespace hanakivan\GoogleUserLogin;

use Illuminate\Support\ServiceProvider;

class GoogleUserLoginServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config.php', "hanakivan");
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/views', 'hanakivan');
        $this->loadMigrationsFrom(__DIR__.'/migrations');

        /*$this->publishes([
            __DIR__.'/views' => resource_path('views/hanakivan'),
        ]);*/
    }
}
