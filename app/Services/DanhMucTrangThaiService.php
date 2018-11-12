<?php

namespace App\Services;

use App\Models\Department;
use App\Http\Resources\DanhMucTrangThaiResource;
use App\Http\Resources\HanhChinhResource;
use App\Repositories\DanhMucTrangThaiRepository;
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
}