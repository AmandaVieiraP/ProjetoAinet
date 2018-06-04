<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssociateMember extends Model
{
	//protected $table = 'associate_members';
	
    public $timestamps  = false;

    protected $fillable = [
    	'main_user_id','associated_user_id'
    ];

    //Para garantir que quando cria coloca o created_at
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
}
