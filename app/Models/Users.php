<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model
{
    use SoftDeletes;

    protected $table = 'auth.users';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $hidden = ['password'];
}
