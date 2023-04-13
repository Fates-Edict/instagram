<?php

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function hValidator($payload = []) {
    $messageFactories = [
        'required' => 'is required.',
        'same' => 'does not match with ',
        'email' => 'not using a right format.',
        'unique' => 'has been taken.'
    ];
    
    $rules = [];
    $message = [];

    foreach($payload as $key => $value) {
        $tempRules = '';
        foreach($value['rules'] as $val) {
            empty($tempRules) ? $tempRules = $val : $tempRules .= '|' . $val;
            if(strpos($val, ':')) {
                $explode = explode(':', $val);
                if($explode[0] === 'same') $message[$key . '.' . $explode[0]] = ucwords($value['alias']) . ' ' . $messageFactories[$explode[0]] . $explode[1] . '.';
                else $message[$key . '.' . $explode[0]] = ucwords($value['alias']) . ' ' . $messageFactories[$explode[0]]; 
            } else $message[$key . '.' . $val] = ucwords($value['alias']) . ' ' . $messageFactories[$val];
        }
        $rules[$key] = $tempRules;
    }
    return [$rules, $message];
}

function hApiResponse($data, $message = 'success', $code = 200)
{
    $res = ['message' => $message, 'data' => $data];
    return response($res, $code);
}

function hGenerateJwtToken($user)
{
    $key = env('JWT_SECRET_KEY', 'asdhaskjdhasjkdhqwuyerfqwytgehvdsanbm');
    $expired = env('JWT_EXPIRED', 120);
    $payload = [
        'iss'       => env('FRONTEND_URL'),
        'data'      => [ 
            'username'  => $user->username,
            'name'      => $user->name,
            'phone'     => $user->phone
        ],
        'iat'       => time(),
        'expired'   =>  Carbon::now()->addMinutes($expired)->timestamp,
    ];

    $encode = JWT::encode($payload, $key, 'HS256');
    return $encode;
}