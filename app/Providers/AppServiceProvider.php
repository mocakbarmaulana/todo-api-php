<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Binding RandomJokeService to the container
        $this->app->singleton(\App\Service\RandomJokeService::class, function ($app) {
            return new \App\Service\RandomJokeService(
                $app->make(\Illuminate\Http\Client\Factory::class)->baseUrl(config('services.random_joke.base_uri'))
            );
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
