<?php

namespace App\Providers;

use App\Services\Auth\AuthService;
use App\Services\Task\TaskService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService();
        });
        
        $this->app->singleton(TaskService::class, function ($app) {
            return new TaskService();
        });
       
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}