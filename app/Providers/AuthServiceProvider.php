<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        // policies
        $this->registerPolicies();

        // gates
        /* php 7.3 en lager*/
        Gate::define("admin", function (User $user) {
            return $user->isAdmin();
        });
        /* vanaf php 7.4 en hoger */
        //        Gate::define('admin', fn() => $user->isAdmin());
    }
}
