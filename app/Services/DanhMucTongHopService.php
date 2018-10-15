<?php

namespace App\Services;

use App\Models\Department;
use App\Http\Resources\DanhMucTongHopResource;
use App\Http\Resources\BenhVienResource;
use App\Http\Resources\TinhResource;
use App\Http\Resources\HanhChinhResource;
use App\Repositories\DanhMucTongHopRepository;
use Illuminate\Http\Request;
use Validator;

class DanhMucTongHopService {
    public function __construct(DanhMucTongHopRepository $danhmuctonghoprepository)
    {
        $this->DanhMucTongHopRepository = $danhmuctonghoprepository;
    }

    public function GetListNgheNghiep()
    {
        return DanhMucTongHopResource::collection(
           $this->DanhMucTongHopRepository->getListNgheNghiep()
        );
    }
    
    public function getListBenhVien()
    {
        return BenhVienResource::collection(
           $this->DanhMucTongHopRepository->getListBenhVien()
        );
    }
    
    public function getListDanToc()
    {
        return DanhMucTongHopResource::collection(
           $this->DanhMucTongHopRepository->getListDanToc()
        );
    }
    
    public function getListQuocTich()
    {
        return DanhMucTongHopResource::collection(
           $this->DanhMucTongHopRepository->getListQuocTich()
        );
    }
    
    public function getListTinh()
    {
        return HanhChinhResource::collection(
           $this->DanhMucTongHopRepository->getListTinh()
        );
    }
    
    public function getListHuyen($matinh)
    {
        return HanhChinhResource::collection(
           $this->DanhMucTongHopRepository->getListHuyen($matinh)
        );
    }
    
    public function getListXa($mahuyen,$matinh)
    {
        return HanhChinhResource::collection(
           $this->DanhMucTongHopRepository->getListXa($mahuyen,$matinh)
        );
    }
}