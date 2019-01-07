<?php

namespace App\Services;

use App\Repositories\Auth\AuthRolesRepository;
use Illuminate\Http\Request;
use Validator;

class AuthRolesService {
    public function __construct(
        AuthRolesRepository $authRolesRepository)
    {
        $this->authRolesRepository = $authRolesRepository;
    }

    public function getListRoles($limit, $page)
    {
        $data = $this->authRolesRepository->getListRoles($limit, $page);
        return $data;
    }

}