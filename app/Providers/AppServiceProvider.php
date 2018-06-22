<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
         $this->app->bind('App\Http\Contracts\ResponseFormatterInterface', 'App\Http\Services\ResponseFormatter');
         $this->app->bind('App\Http\Contracts\DeliveryResourceInterface','App\Http\Services\DeliveryResource');
    }
}
