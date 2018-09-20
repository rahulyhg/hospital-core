<?php

namespace App\Services;

use App\Http\Resources\DepartmentResource;
use App\Repositories\DepartmentRepository;
use Illuminate\Http\Request;

class DepartmentService{
    public function __construct(DepartmentRepository $repository)
    {
        $this->repository = $repository;        
    }
    
    public function getListDepartment(Request $request)
    {
        $offset = $request->query('offset',0);
        return DepartmentResource::collection(
           $this->repository->getDataListDepartment($offset, $request)
        );
    }

}