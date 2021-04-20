<?php

namespace App\Providers;

use App\Observers\SettingsObserver;
use App\Setting;
use Illuminate\Support\Facades\Schema;
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
        if (\config('app.redirect_https')) {
            \URL::forceScheme('https');
        }
        Schema::defaultStringLength(191);

        Setting::observe(SettingsObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (\config('app.redirect_https')) {
            $this->app['request']->server->set('HTTPS', true);
        }
    }
}
