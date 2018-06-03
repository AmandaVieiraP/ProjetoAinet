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
    
    public function user() {
        return $this->belongsTo('App\User', 'owner_id');
    }

    public function movements() {
        return $this->hasMany('App\Movement');
    }

    public function movementsOrderByDateDesc() {
        return $this->hasMany('App\Movement')->orderBy('date', 'desc');
    }

    public function movementsOrderByDateAsc() {
        return $this->hasMany('App\Movement')->orderBy('date', 'asc');
    }

    public function accountType() {
        return $this->belongsTo('App\AccountType');
    }

    /* public function getUpdatedAtColumn() {
        return null;
    } */
}
