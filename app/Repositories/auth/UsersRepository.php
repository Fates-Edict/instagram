<?php

namespace App\Repositories\auth;

use Exception;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;

class UsersRepository
{
    protected $model;

    public function __construct(Users $model)
    {
        $this->model = $model;
    }

    public function initialize($id = null)
    {
        $model = new Users;
        if(!empty($id)) $model = $this->model->where('id', $id)->first();
        return $model;
    }

    public function index($request)
    {
        try {
            $data = $this->model->all();
            return $data;
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function store($request, $id = null)
    {
        try {
            $data = $this->initialize($id);
            $hash = $request->password = Hash::make($request->password);
            $data->name = $request->name;
            $data->username = $request->username;
            $data->password = $hash;
            $data->phone = $request->phone;
            $data->save();
            return $data;
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function login($request)
    {
        try {
            $msg = '';
            $result = false;
            $details = [];
            $user = $this->model->where('username', $request->credential)->orWhere('phone', $request->credential)->first();
            if(!$user) {
                $msg = 'Credentials is not valid.';
                $details = ['credential' => 'Credential is not valid'];
            }
            if(Hash::check($request->password, $user->password)) {
                $details = $user;
                $result = true;
                $msg = 'Login success.';
            } else {
                $msg = 'Credentials is not valid.';
                $details = ['password' => 'Password does not match'];
            }
            return [ 'details' => $details, 'result' => $result, 'message' => $msg ];
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}