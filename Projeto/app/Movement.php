<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Account;

class Movement extends Model
{
	protected $casts = [
    	'value' => 'float',
	];

	protected $fillable = [
         'account_id', 'movement_category_id', 'date', 'value', 'start_balance', 'end_balance', 'description', 'type', 'document_id'
    ];

    public function account() {
        return $this->belongsTo('App\Account');
    }

    public function setUpdatedAtAttribute($value)
	{
	}

}
