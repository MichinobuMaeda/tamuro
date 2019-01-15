<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::resource('groups', 'App\Policies\GroupPolicy', [
            'view' => 'view',
            'create' => 'create',
            'update' => 'update',
            'delete' => 'delete',
            'all' => 'all',
        ]);

        Gate::resource('users', 'App\Policies\UserPolicy', [
            'view' => 'view',
            'create' => 'create',
            'update' => 'update',
            'delete' => 'delete',
            'list' => 'list',
            'invite' => 'invite',
        ]);

        Gate::resource('system', 'App\Policies\SystemPolicy', [
            'administrate' => 'administrate',
        ]);
    }
}
