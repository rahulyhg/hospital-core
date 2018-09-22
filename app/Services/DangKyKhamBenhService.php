<?php

namespace App\Services;

use App\Http\Resources\PatientResource;
use App\Http\Resources\HosobenhanResource;
use App\Repositories\Patient\PatientRepository;
use App\Repositories\DangKyKhamBenhRepository\DangKyKhamBenhRepository;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\ServicepricerefResource;
use Illuminate\Http\Request;
use Validator;

class DangKyKhamBenhService{
    public function __construct(DangKyKhamBenhRepository $repository)
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
    
    public function getListYeuCauKham(Request $request)
    {
        $offset = $request->query('offset',0);
        
        return ServicepricerefResource::collection(
           $this->repository->getDataYeuCauKham($offset, $request)
        );
    }
   
}