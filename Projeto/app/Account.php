<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
         'owner_id', 'account_type_id', 'date','code','description','start_balance','current_balance',
    ];
    //protected $dates = ['deleted_at'];

    public $timestamps = false;
    
    public function user()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }

    public function movements()
    {
        return $this->hasMany('App\Movement');
    }

<<<<<<< HEAD
    public function movementsOrderByDateDesc() {
        return $this->hasMany('App\Movement')->orderBy('date', 'desc');
    }

    public function movementsOrderByDateAsc() {
        return $this->hasMany('App\Movement')->orderBy('date', 'asc');
=======
    public function movementsOrderByDateDesc()
    {
        return $this->hasMany('App\Movement')->orderBy('date');
>>>>>>> 1ac3afbde4c0de38091aa0080292109c7ff0f201
    }

    public function accountType()
    {
        return $this->belongsTo('App\AccountType');
    }
}
