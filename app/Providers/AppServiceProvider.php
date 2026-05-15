<?php

namespace App\Providers;

use App\Repositories\EloquentTaskRepository;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TaskRepositoryInterface::class,
            EloquentTaskRepository::class
        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         RateLimiter::for('api', function (Request $request) {

            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip()
            );

        });
    }
}
