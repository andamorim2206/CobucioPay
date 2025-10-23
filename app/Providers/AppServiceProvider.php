<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Route;

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
        $this->loadRoutesFrom(base_path('routes/web.php'));
        $this->loadRoutesFrom(base_path('routes/api.php'));
    }
}
