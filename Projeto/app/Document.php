<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{

    protected $fillable = [
         'type', 'original_name', 'description', 
    ];

    public function setUpdatedAtAttribute($value)
	{
	} 

}