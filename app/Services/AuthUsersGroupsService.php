<?php

namespace App\Services;

use App\Repositories\Auth\AuthUsersGroupsRepository;
use Illuminate\Http\Request;
use Validator;

class AuthUsersGroupsService {
    public function __construct(
        AuthUsersGroupsRepository $authUsersGroupsRepository)
    {
        $this->authUsersGroupsRepository = $authUsersGroupsRepository;
    }

    public function getAuthGroupsByUsersId($id,$benhVienId)
    {
        $data = $this->authUsersGroupsRepository->getAuthGroupsByUsersId($id,$benhVienId);
        return $data;
    }

}