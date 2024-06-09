<?php

namespace Bidhan\Bhadhan;

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
