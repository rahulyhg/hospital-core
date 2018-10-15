<?php

namespace App\Services;

use App\Models\Phong;
use App\Http\Resources\PhongResource;
use App\Repositories\PhongRepository;
use Illuminate\Http\Request;
use Validator;

class PhongService {
    public function __construct(PhongRepository $phongRepository)
    {
        $this->PhongRepository = $phongRepository;
    }
   
    public function getListPatientByKhoaPhong($loaibenhanid, $departmentid, $id_benh_vien){
        
        
        //return new DepartmentResource($data);
    }

    public function getListPhong($loaiphong,$khoaid)
    {
        return PhongResource::collection(
           $this->PhongRepository->getListPhong($loaiphong,$khoaid)
        );
    }
}