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
        $this->AuthUsersRepository = $authUsersRepository;
        $this->AuthUsersGroupsRepository = $authUsersGroupsRepository;
        $this->AuthGroupsHasRolesRepository = $authGroupsHasRolesRepository;
    }

    public function getUserRolesByEmail($email)
    {
        $id = $this->AuthUsersRepository->getIdbyEmail($email);
        $idgroup = $this->AuthUsersGroupsRepository->getIdGroupbyId($id);
        $roles = $this->AuthGroupsHasRolesRepository->getRolesbyIdGroup($idgroup);
        return $roles;
    }
}