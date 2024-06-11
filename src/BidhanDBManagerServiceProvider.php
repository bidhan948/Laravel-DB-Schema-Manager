<?php

namespace Bidhan\Bhadhan;

use Bidhan\Bhadhan\Interfaces\BhadhanDBManagerServiceInterface;
use Bidhan\Bhadhan\Services\BhadhanDBManagerService;
use Illuminate\Support\ServiceProvider;

class BidhanDBManagerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/bhadhan.php',
            'bhadhan'
        );

        $this->publishes([
            __DIR__ . '/resources/css' => public_path('vendor/bidhan/bhadhan/css'),
        ], 'public');

        $this->app->bind(BhadhanDBManagerServiceInterface::class, BhadhanDBManagerService::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/router.php');

        $this->loadViewsFrom(__DIR__ . '/resources/views/', 'Bhadhan');

        $this->publishes([
            __DIR__ . '/config/bhadhan.php' => config_path('bhadhan.php'),
        ], 'config');
    }
}
