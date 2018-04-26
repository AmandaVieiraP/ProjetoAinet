<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
// Model
{
    protected $fillable = ['name', 'email', 'password', 'type'];
    
    public function typeToStr()
    {
        switch ($this->type) {
            case 0:
                return 'Administrator';
            case 1:
                return 'Publisher';
            case 2:
                return 'Client';
        }

        return 'Unknown';
    }

}
