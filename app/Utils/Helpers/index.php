<?php

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