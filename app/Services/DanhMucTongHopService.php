<?php

namespace App\Services;

use App\Models\Department;
use App\Http\Resources\DanhMucTongHopResource;
use App\Http\Resources\HanhChinhResource;
use App\Repositories\DanhMuc\DanhMucTongHopRepository;
use Illuminate\Http\Request;
use Validator;

class DanhMucTongHopService {
    public function __construct(DanhMucTongHopRepository $danhMucTongHopRepository)
    {
        $this->danhMucTongHopRepository = $danhMucTongHopRepository;
    }

    public function getListNgheNghiep()
    {
        return DanhMucTongHopResource::collection(
           $this->danhMucTongHopRepository->getListNgheNghiep()
        );
    }

    public function getListDanToc()
    {
        return DanhMucTongHopResource::collection(
           $this->danhMucTongHopRepository->getListDanToc()
        );
    }
    
    public function getListQuocTich()
    {
        return DanhMucTongHopResource::collection(
           $this->danhMucTongHopRepository->getListQuocTich()
        );
    }
    
    public function getListTinh()
    {
        return HanhChinhResource::collection(
           $this->danhMucTongHopRepository->getListTinh()
        );
    }
    
    public function getListHuyen($maTinh)
    {
        return HanhChinhResource::collection(
           $this->danhMucTongHopRepository->getListHuyen($maTinh)
        );
    }
    
    public function getListXa($maHuyen,$maTinh)
    {
        return HanhChinhResource::collection(
           $this->danhMucTongHopRepository->getListXa($maHuyen,$maTinh)
        );
    }
    
    public function getListDanhMucTongHop($limit, $page)
    {
        $data = $this->danhMucTongHopRepository->getListDanhMucTongHop($limit, $page);
        
        return $data;
    }
    
    public function getDmthById($dmthId)
    {
        $data = $this->danhMucTongHopRepository->getDataDanhMucTongHopById($dmthId);
        
        return $data;
    }
    
    public function getDanhMucTongHopTheoKhoa($khoa, $limit, $page) {
        $data = $this->danhMucTongHopRepository->getDanhMucTongHopTheoKhoa($khoa, $limit, $page);
        
        return $data;
    }
    
    public function createDanhMucTongHop(array $input)
    {
        $id = $this->danhMucTongHopRepository->createDanhMucTongHop($input);
        return $id;
    }
    
    public function updateDanhMucTongHop($dmthId, array $input)
    {
        $this->danhMucTongHopRepository->updateDanhMucTongHop($dmthId, $input);
    }
    
    public function deleteDanhMucTongHop($dmthId)
    {
        $this->danhMucTongHopRepository->deleteDanhMucTongHop($dmthId);
    }
}