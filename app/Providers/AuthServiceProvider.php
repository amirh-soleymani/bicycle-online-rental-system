<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Bicycle;
use App\Policies\BicyclePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Bicycle::class => BicyclePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('admin-report', function ($user){
            return $user->type == 'admin';
        });

        Gate::define('member-report', function ($user){
            return $user->type == 'member';
        });
        Gate::define('rent-bicycle', function ($user){
            return $user->type == 'member';
        });

    }
}
