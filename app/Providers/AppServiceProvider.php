<?php

namespace App\Providers;

use App\Repositories\BicycleRepository;
use App\Repositories\BicycleRepositoryInterface;
use App\Services\BicycleService;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BicycleRepositoryInterface::class, BicycleRepository::class);
        $this->app->bind(BicycleService::class, function ($app) {
            return new BicycleService($app->make(BicycleRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('successResponse', function ($message, $data) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $data
            ]);
        });

        Response::macro('errorResponse', function ($message, $data, $statusCode) {
            return response()->json([
                'status' => 'error',
                'message' => $message,
                'data' => $data
            ], $statusCode);
        });
    }
}
