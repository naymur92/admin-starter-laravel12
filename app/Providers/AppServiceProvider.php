<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Passport will use default storage/oauth keys location
        // Passport::loadKeysFrom(storage_path(''));

        Passport::enablePasswordGrant();

        Passport::tokensExpireIn(CarbonInterval::hours(3));
        Passport::refreshTokensExpireIn(CarbonInterval::days(15));
        Passport::personalAccessTokensExpireIn(CarbonInterval::months(6));
    }
}
