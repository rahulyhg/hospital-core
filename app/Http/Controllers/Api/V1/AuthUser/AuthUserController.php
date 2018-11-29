<?php
namespace App\Http\Controllers\Api\V1\AuthUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\AuthUsersService;
use App\Http\Requests\AuthUserFormRequest;
use App\Http\Requests\UpdateAuthUsersFormRequest;

class AuthUserController extends APIController
{
    public function __construct(AuthUsersService $authUsersService)
    {
        $this->authUsersService = $authUsersService;
    }

    public function getListNguoiDung(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        
        $data = $this->authUsersService->getListNguoiDung($limit, $page);
        return $this->respond($data);
    }
    
    public function getAuthUsersById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->authUsersService->getAuthUsersById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function createAuthUsers(AuthUserFormRequest $request)
    {
        $input = $request->all();
        
        $id = $this->authUsersService->createAuthUsers($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function deleteAuthUsers($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $this->authUsersService->deleteAuthUsers($id);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }

    public function checkEmailbyEmail($email)
    {
        $data = $this->authUsersService->checkEmailbyEmail($email);
        return $this->respond($data);
    }
    
    public function updateAuthUsers($id,UpdateAuthUsersFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->authUsersService->updateAuthUsers($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }    

}