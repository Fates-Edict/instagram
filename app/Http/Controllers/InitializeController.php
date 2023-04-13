<?php

namespace App\Http\Controllers;

use App\Models\UserFriendships;
use App\Models\Users;

class InitializeController extends Controller
{
    public function init()
    {
        $users = Users::all();
        foreach($users as $user) {
            UserFriendships::where('user_id', $user->id)->where('follower_id', $user->id)->delete();
        }
    }
}