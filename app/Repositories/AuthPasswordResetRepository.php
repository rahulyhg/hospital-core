<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Auth\AuthPasswordReset; 

class AuthPasswordResetRepository extends BaseRepositoryV2 {
    
    public function getModel()
    {
        return AuthPasswordReset::class;
    }
    
    public function updateOrCreate($email) {
        $passwordReset = $this->model->updateOrCreate(
            ['email' => $email],
            [
                'email' => $email,
                'token' => str_random(60)
             ]
        );     
        return $passwordReset;
    }
    
    public function findByToken($token) {
        $passwordReset = $this->model->where('token', $token)->first();
        if($passwordReset) return $passwordReset;
    }
    
    public function findByTokenAndEmail($token, $email) {
        $passwordReset = $this->model->where([
            ['token', $token],
            ['email', $email]
        ])->first();
        if($passwordReset) return $passwordReset;
    }
}