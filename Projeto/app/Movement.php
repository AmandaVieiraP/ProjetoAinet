<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Account;

class Movement extends Model
{
    public function account() {
        return $this->belongsTo('App\Account');
    }

}
