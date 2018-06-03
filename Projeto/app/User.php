<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'name', 'email', 'password','phone','profile_photo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function associated_of() {
        return $this->belongsToMany(User::class, 'associate_members', 'associated_user_id', 'main_user_id')->withPivot('created_at');
    }
    
    public function associateds() {
        return $this->belongsToMany(User::class, 'associate_members', 'main_user_id', 'associated_user_id')->withPivot('created_at');
    }

    public function allAccounts() {
        return $this->hasMany('App\Account', 'owner_id')->withTrashed();
    }

    public function closedAccounts() {
        return $this->hasMany('App\Account', 'owner_id')->onlyTrashed();
    }

    public function openAccounts(){
        return $this->hasMany('App\Account', 'owner_id');
    }

    public function movements() {
        return $this->hasMany('App\Movement');
    }

    public function isOwner($account_id)
    {
        $useraccounts = $this->allAccounts;
          foreach ($useraccounts as $acc) {
              if($acc->id == $account_id){
                return true;
              }
          }
         
            return false;
    }
}
