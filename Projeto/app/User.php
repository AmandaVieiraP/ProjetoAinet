<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

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

    public function accounts() {
        return $this->hasMany('App\Account', 'owner_id');
    }

    
}
