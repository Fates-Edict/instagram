<?php

namespace App\Http\Controllers\auth;
use App\Http\Controllers\Controller;
use App\Repositories\auth\UsersRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class UsersController extends Controller
{
    protected $repository;

    public function __construct(UsersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        try {
            $response = $this->repository->index($request);
            return hApiResponse($response);
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $response = $this->repository->login($request);
            $statusCode = 200;
            if(!$response['result']) $statusCode = 404;
            return hApiResponse($response['details'], $response['message'], $statusCode);
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function store(Request $request, $id = null)
    {
        try {
            $payload = [
                'name' => [
                    'alias' => 'full name',
                    'rules' => ['required']
                ],
                'username' => [
                    'alias' => 'username',
                    'rules' => ['required', 'unique:pgsql.auth.users']
                ],
                'phone' => [
                    'alias' => 'phone number',
                    'rules' => ['required', 'unique:pgsql.auth.users']
                ],
                'password' => [
                    'alias' => 'password',
                    'rules' => ['required']
                ]
            ];

            $hValidator = hValidator($payload);
            $validator = Validator::make($request->all(), $hValidator[0], $hValidator[1]);
            if($validator->fails()) return hApiResponse(null, $validator->errors(), 400);
            $response = $this->repository->store($request, $id);
            $msg = $id ? 'Update profile success' : 'Register success';
            return hApiResponse($response, $msg, 201);
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}