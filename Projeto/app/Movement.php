<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Account;

class Movement extends Model
{
    protected $casts = [
        'value' => 'float',
    ];

    public $timestamps  = false;

    protected $fillable = [
         'account_id', 'movement_category_id', 'date', 'value', 'start_balance', 'end_balance', 'description', 'type', 'document_id'
    ];

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function document()
    {
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
