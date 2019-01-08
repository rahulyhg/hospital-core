<?php

namespace App\Services;

use App\Repositories\Auth\AuthGroupsHasRolesRepository;

class AuthGroupsHasRolesService
{
    public function __construct(AuthGroupsHasRolesRepository $authGroupsHasRolesRepository)
    {
        $this->authGroupsHasRolesRepository = $authGroupsHasRolesRepository; 
    }
    
    public function getRolesByGroupsId($id)
    {
        $data = $this->authGroupsHasRolesRepository->getRolesByGroupsId($id);
        return $data;
    }
}