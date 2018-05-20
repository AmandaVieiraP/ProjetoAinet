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

        //'admin' nome do gate Vê se o user é administrador
        Gate::define('admin', function ($user) {
            return $user->admin == true;
        });
         //user so pode editar contas que lhe pertençam
        Gate::define('edit-account', function ($user, $account_id) {
           return $user->isOwner($account_id);
        });

    }
}
