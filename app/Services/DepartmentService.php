<?php

namespace App\Services;

use App\Models\Department;
use App\Http\Resources\DepartmentResource;
use App\Repositories\SttDontiep\DepartmentRepository;
use Illuminate\Http\Request;
use Validator;

class DepartmentService {
    public function __construct(DepartmentRepository $DepartmentRepository)
    {
        $this->DepartmentRepository = $DepartmentRepository;
    }
   
    public function getListPatientByKhoaPhong($loaibenhanid, $departmentid, $id_benh_vien){
        
        
        //return new DepartmentResource($data);
    }
}