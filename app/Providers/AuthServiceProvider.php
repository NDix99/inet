<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Mendefinisikan gate untuk peran pengguna
        Gate::define('superadmin', function ($user) {
            return $user->isSuperAdmin();
        });

        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('technician', function ($user) {
            return $user->isTechnician();
        });
    }
}
