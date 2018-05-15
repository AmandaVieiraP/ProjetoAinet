<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    //
    use SoftDeletes;
	//protected $dates = ['deleted_at'];

    public $timestamps = false;
	
    public function user() {
        return $this->belongsTo('App\User', 'owner_id');
    }

    public function movements() {
        return $this->hasMany('App\Movement');
    }

    public function movementsOrderByDateDesc() {
        return $this->hasMany('App\Movement')->orderBy('date');
    }

    /* public function getUpdatedAtColumn() {
        return null;
    } */
}
