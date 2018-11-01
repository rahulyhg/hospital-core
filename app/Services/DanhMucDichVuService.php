<?php

namespace App\Services;

use App\Http\Resources\DanhMucDichVuResource;
use App\Repositories\DanhMucDichVuRepository;
use Illuminate\Http\Request;

class DanhMucDichVuService
{
    public function __construct(DanhMucDichVuRepository $repository)
    {
        $this->repository = $repository;        
    }
    
    public function getListYeuCauKham(Request $request)
    {
        //$offset = $request->query('offset',0);
        
        return DanhMucDichVuResource::collection(
           $this->repository->getDataYeuCauKham($request)
        );
    }
    
    public function getListDanhMucDichVu($limit, $page)
    {
        $data = $this->repository->getListDanhMucDichVu($limit, $page);
        
        return $data;
    }
    
    public function getDmdvById($dmdvId)
    {
        $data = $this->repository->getDataDanhMucDichVuById($dmdvId);
        
        return $data;
    }

    public function createDanhMucDichVu(Request $request)
    {
        $id = $this->repository->createDanhMucDichVu($request);
        
        return $id;
    }
    
    public function updateDanhMucDichVu($dmdvId, Request $request)
    {
        $this->repository->updateDanhMucDichVu($dmdvId, $request);
    }
    
    public function deleteDanhMucDichVu($dmdvId)
    {
        $this->repository->deleteDanhMucDichVu($dmdvId);
    }
}