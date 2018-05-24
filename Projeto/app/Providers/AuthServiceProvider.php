<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
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

        Gate::define('view-account-movements', function($user, $account) {
            $accountOwner = $account->user;
            return $user->id == $accountOwner->id;
        });

        //
    }
}
