<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\RegisterFormRequest;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(AuthService $service)
    {
        
        $this->authservice = $service;
    }
    public function register(RegisterFormRequest $request)
    {
        
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();
        return response([
            'status' => 'success',
            'data' => $user
        ], 201);

   
        
        
    }
    public function login(Request $request)
    {
        
        $credentials = $request->only('email', 'password');
        
        if (!$token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'
            ], 400);
        }
        $roles = $this->authservice->getUserRolesByEmail($request->email);
        $extraPayload = array(
            'roles' => $roles
        );
        return response([
            'status' => 'success',
            'token' => $token,
            'payload' => $extraPayload
        ]);
    }
    

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }
    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request) {
        $this->validate($request, ['token' => 'required']);
        
        try {
            JWTAuth::invalidate($request->input('token'));
            return response([
            'status' => 'success',
            'msg' => 'You have successfully logged out.'
        ]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response([
                'status' => 'error',
                'msg' => 'Failed to logout, please try again.'
            ]);
        }
    }
    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }
}