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

    public function Following()
    {
        return $this->hasMany(UserFriendships::class, 'user_id', 'id');
    }

    public function Followers()
    {
        return $this->hasMany(UserFriendships::class, 'follower_id', 'id');
    }
}
