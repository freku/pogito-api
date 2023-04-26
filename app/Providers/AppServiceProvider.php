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
        // Repositories
        $this->app->bind(
            \App\Repositories\Interfaces\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );

        // Services
        $this->app->bind(
            \App\Services\Interfaces\TwitchServiceInterface::class,
            \App\Services\TwitchService::class
        );

        $this->app->bind(
            \App\Services\Interfaces\PostServiceInterface::class,
            \App\Services\PostService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
