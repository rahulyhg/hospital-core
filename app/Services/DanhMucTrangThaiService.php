<?php

namespace App\Services;

use App\Models\Department;
use App\Http\Resources\DanhMucTrangThaiResource;
use App\Http\Resources\HanhChinhResource;
use App\Repositories\DanhMuc\DanhMucTrangThaiRepository;
use Illuminate\Http\Request;
use Validator;

class DanhMucTrangThaiService {
    public function __construct(DanhMucTrangThaiRepository $danhMucTrangThaiRepository)
    {
        $this->danhMucTrangThaiRepository = $danhMucTrangThaiRepository;
    }

    public function getListLoaiVienPhi()
    {
        return DanhMucTrangThaiResource::collection(
           $this->danhMucTrangThaiRepository->getListLoaiVienPhi()
        );
    }

    public function getListDoiTuongBenhNhan()
    {
        return DanhMucTrangThaiResource::collection(
           $this->danhMucTrangThaiRepository->getListDoiTuongBenhNhan()
        );
    }
    
    public function getListKetQuaDieuTri()
    {
        return DanhMucTrangThaiResource::collection(
           $this->danhMucTrangThaiRepository->getListKetQuaDieuTri()
        );
    }
    
    public function getListGiaiPhauBenh()
    {
        return DanhMucTrangThaiResource::collection(
           $this->danhMucTrangThaiRepository->getListGiaiPhauBenh()
        );
    }
    
    public function getListXuTri()
    {
        return DanhMucTrangThaiResource::collection(
           $this->danhMucTrangThaiRepository->getListXuTri()
        );
    }
}