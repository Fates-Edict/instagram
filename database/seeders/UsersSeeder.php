<?php

namespace Database\Seeders;

use App\Models\Users;
use App\Models\UserFriendships;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [];

        for($i = 0; $i < 10; $i++) {
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, "https://random-data-api.com/api/v2/users?size=100&response_type=json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            $response = curl_exec($ch);
            curl_close($ch);
            $datas = json_decode($response);
            foreach($datas as $data) { $users[] = $data; }
        }

        Users::create([
            'name' => 'Mutaqin Yusuf',
            'username' => 'mutaqinyusuf',
            'phone' => '081386683472',
            'password' => Hash::make('emys281252')
        ]);
        foreach($users as $user) {
            Users::create([
                'name' => $user->first_name . ' ' . $user->last_name,
                'username' => $user->username,
                'password' => Hash::make('password'),
                'profile' => $user->avatar
            ]);
        }

        $getUsers = Users::all();

        foreach($getUsers as $key => $value) {
            $randomIteration = rand(50, 1000);
            for($i = 1; $i < $randomIteration; $i++) {
                UserFriendships::create([
                    'user_id' => $value->id,
                    'follower_id' => $i
                ]);
            }
        }
    }
}
