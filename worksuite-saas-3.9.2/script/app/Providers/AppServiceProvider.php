<?php

namespace App\Providers;

use App\Notifications\MyDatabaseChannel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Laravel\Cashier\Cashier;
use Illuminate\Notifications\Channels\DatabaseChannel as BaseDatabaseChannel;

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

        Validator::extend('sub_domain', function ($attribute, $value, $parameters, $validator) {
            $value = explode('.'.get_domain(), $value)[0];
            return preg_match('/[^A-Za-z0-9]+/i', $value) === 0;
        }, 'The :attribute can only contain alphabets and numbers');

        $this->app->instance(BaseDatabaseChannel::class, new MyDatabaseChannel());

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
        Cashier::ignoreMigrations();
    }
}
