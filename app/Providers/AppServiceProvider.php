<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Services\UserPanelService;
use Services\PanelService;
use Services\UserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserService::class, function () {
            return new UserService();
        });

        $this->app->bind(PanelService::class, function () {
            return new PanelService();
        });

        $this->app->bind(UserPanelService::class, function () {
            return new UserPanelService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
