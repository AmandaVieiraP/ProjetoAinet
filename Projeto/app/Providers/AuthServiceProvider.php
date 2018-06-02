<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\User;
use App\Movement;
use App\Account;

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

        Gate::define('download-document',function ($user, $document_id){

            $movement=Movement::where('document_id',$document_id)->first();

            $account=Account::findOrFail($movement->account_id);

            $users = Auth::user()->associateds;

            foreach ($users as $u) {
                if($user==$u)
                    return true;
            }
            

            $documentOwner = Account::findOrFail($document_id)->user;
            $associateds = $user->associated_of; 
            foreach ($associateds as $u) {
                if($u->id==$documentOwner->id)
                    return true;
            }

            return $user->id==$account->owner_id;

        });

        Gate::define('view-account-movements', function($user, $account) {
            $accountOwner = $account->user;
            return $user->id == $accountOwner->id;
        });

        Gate::define('view-accounts', function ($user, $userId){
         
            $associated_of = $user->associated_of;  
            foreach ($associated_of as $u) {
                if($u->id==$userId)
                    return true;
            }

            $associateds = $user->associateds;
            
            foreach ($associated_of as $u) {
                if($u->id==$userId)
                    return false;
            }
            
            return $user->id == $userId;
           
        });
    
        Gate::define('view-movements', function($user, $account) {
            
            $accountOwner = Account::findOrFail($account)->user;
          
            $associateds = $user->associated_of;
            foreach ($associateds as $u) {
                if($u->id==$accountOwner->id)
                    return true;
            }
            return $user->id == $accountOwner->id;
        });

        
    }
}
