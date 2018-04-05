<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    protected $primaryKey = 'user_id';
    protected $table ='users';
    
    public function getRememberToken()
    {
        return null; // not supported
    }
    
    public function setRememberToken($value)
    {
    // not supported
    }
}
