<?php

namespace App\Services;

use App\Repositories\DanhMuc\DanhMucThuocVatTuRepository;
use Illuminate\Http\Request;

class DanhMucThuocVatTuService
{
    public function __construct(DanhMucThuocVatTuRepository $repository)
    {
        $this->repository = $repository;        
    }
    
    // public function getListDanhMucThuocVatTu($limit, $page)
    // {
    //     $data = $this->repository->getListDanhMucThuocVatTu($limit, $page);
        
    //     return $data;
    // }
    
    // public function getDmdvById($dmdvId)
    // {
    //     $data = $this->repository->getDataDanhMucThuocVatTuById($dmdvId);
        
    //     return $data;
    // }

    // public function createDanhMucThuocVatTu(array $input)
    // {
    //     $id = $this->repository->createDanhMucThuocVatTu($input);
        
    //     return $id;
    // }
    
    // public function updateDanhMucThuocVatTu($dmdvId, array $input)
    // {
    //     $this->repository->updateDanhMucThuocVatTu($dmdvId, $input);
    // }
    
    // public function deleteDanhMucThuocVatTu($dmdvId)
    // {
    //     $this->repository->deleteDanhMucThuocVatTu($dmdvId);
    // }
    
    public function getThuocVatTuByLoaiNhom($loaiNhom)
    {
        $data = $this->repository->getThuocVatTuByLoaiNhom($loaiNhom);
        
        return $data;
    }
    
    public function getThuocVatTuByCode($maNhom)
    {
        $data = $this->repository->getThuocVatTuByCode($maNhom);
        
        return $data;
    }
}