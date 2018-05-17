<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
         'type', 'original_name', 'description',
    ];

    public $timestamps  = false;

    public function movement() {
        return $this->belongsTo('App\Movement','document_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
}
