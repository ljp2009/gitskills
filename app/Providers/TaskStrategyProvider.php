<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\UmeiiiHandlers\TaskHandlers\TaskStrategy;

class TaskStrategyProvider extends ServiceProvider
{
    protected $defer = true;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Task\TaskStrategy', function(Request $request, $app){
            if($request->taskId == 0){
                
            }
        });
        
    }
}
