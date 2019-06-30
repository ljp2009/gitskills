<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\User;
use App\Models\UserAttr;
use App\Models\UserSum;
use App\Models\UserDetailStatus;

use App\Models\Ip;
use App\Models\IpAttr;
use App\Models\IpTag;
Use App\Models\IpSum;
use App\RecommendAlgorithm as AR;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
      // $userRecRouter = new AR\UserRecommendRouter();
       //绑定用户属性变监听事件
      // $userRecRouter->bindUserRouteEvent();
       //绑定Ip属性监听事件
       //$userRecRouter->bindIpCalculateEvent();
    }
}
