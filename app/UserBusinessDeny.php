<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBusinessDeny extends Model
{
    protected $table ='user_business_deny';
    protected $fillable = ['users_user_id', 'business_id'];
    public $timestamps = false;
}
