<?php

namespace App\Services;

use App\Repositories\AuthUsersRepository;
use App\Repositories\AuthPasswordResetRepository;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuthUsersService
{
    public function __construct(AuthUsersRepository $authUsersRepository, AuthPasswordResetRepository $authPasswordResetRepository)
    {
        $this->authUsersRepository = $authUsersRepository;      
        $this->authPasswordResetRepository = $authPasswordResetRepository;
    }
    
    public function getListNguoiDung($limit, $page)
    {
        $data = $this->authUsersRepository->getListNguoiDung($limit, $page);
        
        return $data;
    }
    public function getAuthUsersById($id)
    {
        $data = $this->authUsersRepository->getAuthUsersById($id);
        return $data;
    }
    public function createAuthUsers(array $input)
    {
        $id = $this->authUsersRepository->createAuthUsers($input);
        return $id;
    }
    public function deleteAuthUsers($id)
    {
        $this->authUsersRepository->deleteAuthUsers($id);
    }
    
    public function checkEmailbyEmail($email)
    {
        $data = $this->authUsersRepository->checkEmailbyEmail($email);
        return $data;
    }
    
    public function updateAuthUsers($id, array $input)
    {
        $this->authUsersRepository->updateAuthUsers($id, $input);
    }    
    
    public function createToken($request) {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        
        $user = $this->authUsersRepository->checkEmailbyEmail($request->email);
        if (!$user) {
            $data = array(
                'statusCode' => 404,
                'status'  => 'error',
                'message' => "We can't find a user with that e-mail address."
            );
            return $data;
        }
        
        $passwordReset = $this->authPasswordResetRepository->updateOrCreate($request->email);
        
        if ($user && $passwordReset) {
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        }
        return array(
            'status'  => 'success',
            'message' => 'We have e-mailed your password reset link!'
        );
    }
    
    public function find($token) {
        $passwordReset = $this->authPasswordResetRepository->findByToken($token);
        if (!$passwordReset) {
            $data = array(
                'statusCode' => 404,
                'status'  => 'error',
                'message' => 'This password reset token is invalid.',
            );
            return $data;     
        }
        
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            $data = array(
                'statusCode' => 404,
                'status'  => 'error',
                'message' => 'This password reset token is invalid.'
            );
            return $data;
        }
        return $passwordReset;
    }
    
    public function resetPassword($request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);
        
        $passwordReset = $this->authPasswordResetRepository->findByTokenAndEmail($request->token, $request->email);
        
        if (!$passwordReset) {
            $data = array(
                'statusCode' => 404,
                'status'  => 'error',
                'message' => 'This password reset token is invalid.',
            );
            return $data;     
        }
        
        $user = $this->authUsersRepository->checkEmailbyEmail($request->email);
        if (!$user) {
            $data = array(
                'statusCode' => 404,
                'status'  => 'error',
                'message' => "We can't find a user with that e-mail address."
            );
            return $data; 
        }
        
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return $user;
    }
}