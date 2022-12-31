<?php

namespace App\Providers;

use App\Models\User;
use App\Models\BlogPost;
use App\Policies\UserPolicy;
use App\Policies\BlogPostPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $polices = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        BlogPost::class => BlogPostPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function ($user) {
            return $user->is_admin;
        });

        Gate::before(function($user, $ability) {
            if ((in_array($ability, ['update', 'delete', 'restore']) && $user->is_admin)) {
                return true;
            }
        });

    }
}
