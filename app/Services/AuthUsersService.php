<?php

namespace App\Services;

use App\Repositories\AuthUsersRepository;
use Illuminate\Http\Request;

class AuthUsersService
{
    public function __construct(AuthUsersRepository $authUsersRepository)
    {
        $this->authUsersRepository = $authUsersRepository;        
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
}