<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use Illuminate\Http\Middleware\HandleCors;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\SubstituteBindings;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
        if ($this->app->runningInConsole()) {
            $this->app->register('CrestApps\CodeGenerator\CodeGeneratorServiceProvider');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Alias middleware
        Route::aliasMiddleware('role', CheckRole::class);

       

        // Configure CORS
        config([
            'cors' => [
                'paths' => ['api/*', 'sanctum/csrf-cookie'],
                'allowed_methods' => ['*'],
                'allowed_origins' => ['*'],
                //'http://primeproperty.test',
                //'http://192.168.1.6:8081',
                //'exp://192.168.1.6:8081',
                //'http://localhost:8081',
              // In production, specify your app's URL
                'allowed_origins_patterns' => [],
                'allowed_headers' => ['*'],
                'exposed_headers' => [],
                'max_age' => 0,
                'supports_credentials' => false,
            ],
        ]);
    }
}