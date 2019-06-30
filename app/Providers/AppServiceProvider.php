<?php

namespace App\Providers;

use App\Common\RecommendAlgorithm as RA;
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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('algorithm', function ($app) {
            return new RA($app->make('auth')->user());
        });
    }
}
