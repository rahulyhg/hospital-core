<?php
namespace App\Http\Controllers\Api\V1\AuthUser;

use App\Http\Controllers\Api\V1\APIController;
use Illuminate\Http\Request;
use App\Services\AuthUsersService;

class AuthPasswordResetController extends APIController {
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
    */
    
    public function __construct(AuthUsersService $authUsersService)
    {
        $this->authUsersService = $authUsersService;      
    }
     
    public function create(Request $request) {
        $responseData = $this->authUsersService->createToken($request);
        if($responseData['status'] == 'error') {
            $this->setStatusCode($responseData['statusCode']);
            $data['message'] = $responseData['message'];
            return $this->respond($data);
        }
        return $this->respond($responseData); 
    }
    
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
    */
    
    public function find($token) {
        $responseData = $this->authUsersService->find($token);
        if($responseData['status'] == 'error') {
            $this->setStatusCode($responseData['statusCode']);
            $data['message'] = $responseData['message'];
            return $this->respond($data);
        }
        return $this->respond($responseData); 
    }
    
    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
    */

    public function reset(Request $request) {
        $responseData = $this->authUsersService->resetPassword($request);
        if($responseData['status'] == 'error') {
            $this->setStatusCode($responseData['statusCode']);
            $data['message'] = $responseData['message'];
            return $this->respond($data);
        }
        return $this->respond($responseData); 
    }
}