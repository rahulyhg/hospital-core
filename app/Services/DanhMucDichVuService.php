<?php

namespace App\Services;

use App\Http\Resources\DanhMucDichVuResource;
use App\Repositories\DanhMuc\DanhMucDichVuRepository;
use Illuminate\Http\Request;

class DanhMucDichVuService
{
    public function __construct(DanhMucDichVuRepository $repository)
    {
        $this->repository = $repository;        
    }
    
    public function getListYeuCauKham(Request $request)
    {
        return DanhMucDichVuResource::collection(
           $this->repository->getDataYeuCauKham($request)
        );
    }
    
    public function getListDanhMucDichVu($limit, $page, $loaiNhom)
    {
        $data = $this->repository->getListDanhMucDichVu($limit, $page, $loaiNhom);
        
        return $data;
    }
    
    public function getDmdvById($dmdvId)
    {
        $data = $this->repository->getDataDanhMucDichVuById($dmdvId);
        
        return $data;
    }

    public function createDanhMucDichVu(array $input)
    {
        $id = $this->repository->createDanhMucDichVu($input);
        
        return $id;
    }
    
    public function updateDanhMucDichVu($dmdvId, array $input)
    {
        $this->repository->updateDanhMucDichVu($dmdvId, $input);
    }
    
    public function deleteDanhMucDichVu($dmdvId)
    {
        $this->repository->deleteDanhMucDichVu($dmdvId);
    }
    
    public function getYLenhByLoaiNhom($loaiNhom)
    {
        $data = $this->repository->getYLenhByLoaiNhom($loaiNhom);
        
        return $data;
    }
    
    public function getDanhMucDichVuPhongOc() {
        $data = $this->repository->getDanhMucDichVuPhongOc();
        
        return $data;
    }
}