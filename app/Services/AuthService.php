<?php

namespace App\Services;

use App\Http\Resources\BenhVienResource;
use App\Repositories\AuthUsersRepository;
use App\Repositories\AuthUsersGroupsRepository;
use App\Repositories\AuthGroupsHasRolesRepository;
use App\Http\Resources\AuthResource;
use Illuminate\Http\Request;
use Validator;

class AuthService {
    public function __construct(
        AuthUsersRepository $authUsersRepository, 
        AuthUsersGroupsRepository $authUsersGroupsRepository,
        AuthGroupsHasRolesRepository $authGroupsHasRolesRepository )
    {
        $this->authUsersRepository = $authUsersRepository;
        $this->authUsersGroupsRepository = $authUsersGroupsRepository;
        $this->authGroupsHasRolesRepository = $authGroupsHasRolesRepository;
    }

    public function getUserRolesByEmail($email)
    {
        $id = $this->authUsersRepository->getIdbyEmail($email);
        $idGroup = $this->authUsersGroupsRepository->getIdGroupbyId($id->id);
        $roles = $this->authGroupsHasRolesRepository->getRolesbyIdGroup($idGroup);
        $arr = [$id->username,$roles];
        return $roles;
    }
}