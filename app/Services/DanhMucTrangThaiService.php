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
    
    public function getListDanhMucTrangThai($limit, $page, $dienGiai, $khoa)
    {
        $data = $this->danhMucTrangThaiRepository->getListDanhMucTrangThai($limit, $page, $dienGiai, $khoa);
        return $data;
    }
    
    public function getDanhMucTrangThaiTheoKhoa($khoa, $limit, $page) {
        $data = $this->danhMucTrangThaiRepository->getDanhMucTrangThaiTheoKhoa($khoa, $limit, $page);
        return $data;
    }
    
    public function getDanhMucTrangThaiById($id)
    {
        return $this->danhMucTrangThaiRepository->getDanhMucTrangThaiById($id);
    }
    
    public function createDanhMucTrangThai(array $input)
    {
        $id = $this->danhMucTrangThaiRepository->createDanhMucTrangThai($input);
        return $id;
    }
    
    public function updateDanhMucTrangThai($dmttId, array $input)
    {
        $result = $this->danhMucTrangThaiRepository->updateDanhMucTrangThai($dmttId, $input);
        return $result;
    }
    
    public function deleteDanhMucTrangThai($dmttId)
    {
        $this->danhMucTrangThaiRepository->deleteDanhMucTrangThai($dmttId);
    }
    
    public function getAllKhoa()
    {
        $data = $this->danhMucTrangThaiRepository->getAllKhoa();
        return $data;
    }
    
    public function getListHinhThucChuyen()
    {
        return DanhMucTrangThaiResource::collection(
           $this->danhMucTrangThaiRepository->getListHinhThucChuyen()
        );
    }
    
    public function getListTuyen()
    {
        return DanhMucTrangThaiResource::collection(
           $this->danhMucTrangThaiRepository->getListTuyen()
        );
    }
    
    public function getListLyDoChuyen()
    {
        return DanhMucTrangThaiResource::collection(
           $this->danhMucTrangThaiRepository->getListLyDoChuyen()
        );
    }
}