<?php

namespace App\Services;

use App\Repositories\Auth\AuthGroupsRepository;
use App\Repositories\Auth\AuthGroupsHasRolesRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuthGroupsService
{
    public function __construct(AuthGroupsRepository $authGroupsRepository,AuthGroupsHasRolesRepository $authGroupsHasRolesRepository)
    {
        $this->authGroupsRepository = $authGroupsRepository;
        $this->authGroupsHasRolesRepository = $authGroupsHasRolesRepository; 
    }
    
    public function getListAuthGroups($limit, $page, $keyWords)
    {
        $data = $this->authGroupsRepository->getListAuthGroups($limit, $page, $keyWords);
        return $data;
    }
    
    public function getByListId($limit,$page,$id)
    {
        $data = $this->authGroupsRepository->getByListId($limit,$page,$id);
        return $data;
    }
    
    public function createAuthGroups(array $input)
    {
        $id = $this->authGroupsRepository->createAuthGroups($input);
        return $id;
    }
    
    public function getAuthGroupsById($id)
    {
        $data = $this->authGroupsRepository->getAuthGroupsById($id);
        return $data;
    }
    
    public function updateAuthGroups($id,array $input)
    {
        $this->authGroupsHasRolesRepository->updateAuthGroupsHasRoles($id, $input['rolesSelected']);
        $this->authGroupsRepository->updateAuthGroups($id, $input);
    }
    
    public function getKhoaPhongByGroupsId($id)
    {
        $data = $this->authGroupsRepository->getKhoaPhongByGroupsId($id);
        return $data;
    }    
}