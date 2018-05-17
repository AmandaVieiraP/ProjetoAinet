<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Account;

class Movement extends Model
{
	public $timestamps  = false;

    public function account() {
        return $this->belongsTo('App\Account');
    }

     public function document() {
        return $this->belongsTo('App\Document');
    }

    //Para garantir que quando cria coloca o created_at
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

}
