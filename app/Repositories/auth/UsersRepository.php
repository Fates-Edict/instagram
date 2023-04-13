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
            if($request->has('search')) {
                $data = $this->model->select(['username', 'name', 'profile'])->where('username', 'LIKE', '%' . $request->search . '%')->limit(50)->withCount('Followers')->get();
            } else $data = $this->model->all();
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
            } else {
                if(Hash::check($request->password, $user->password)) {
                    unset($user->id);
                    unset($user->phone);
                    unset($user->created_at);
                    unset($user->created_by);
                    unset($user->deleted_at);
                    unset($user->deleted_by);
                    unset($user->updated_at);
                    unset($user->updated_by);
                    $details = ['data' => $user, 'token' => hGenerateJwtToken($user)];
                    $result = true;
                    $msg = 'Login success.';
                } else {
                    $msg = 'Credentials is not valid.';
                    $details = ['password' => 'Password does not match'];
                }
            }
            return [ 'details' => $details, 'result' => $result, 'message' => $msg ];
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}