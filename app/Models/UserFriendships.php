<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFriendships extends Model
{
    use HasFactory;

    protected $table = 'auth.user_friendships';
    protected $guarded = ['id'];

    public function User()
    {
        return $this->belongsTo(Users::class);
    }
}
