<?php

namespace App\Http\Controllers\Api\V1\DangKyKhamBenh;

use Illuminate\Http\Request;
use App\Services\PhongService;
use App\Services\DanhMucDichVuService;
use App\Services\DanhMucTongHopService;
use App\Services\DanhMucBenhVienService;
use App\Services\DanhMucTrangThaiService;
use App\Services\BenhVienService;
use App\Http\Controllers\API\V1\APIController;

class DangKyKhamBenhController extends APIController
{
    /**
     * __construct.
     *
     * @param DangKyKhamBenhService $service
     */
    public function __construct(
        PhongService $phongService, 
        DanhMucDichVuService $danhMucDichVuService,
        DanhMucTongHopService $danhMucTongHopService,
        DanhMucBenhVienService $danhMucBenhVienService,
        BenhVienService $benhVienService,
        DanhMucTrangThaiService $danhMucTrangThaiService
        )
    {
        $this->phongService = $phongService;
        $this->danhMucDichVuService = $danhMucDichVuService;
        $this->danhMucTongHopService = $danhMucTongHopService;
        $this->danhMucBenhVienService = $danhMucBenhVienService;
        $this->benhVienService = $benhVienService;
        $this->danhMucTrangThaiService = $danhMucTrangThaiService;
    }
    
    // get danh sach phong kham theo departmentgroupid va departmenttype
    public function getListPhong(Request $request)
    {
        $data = $this->phongService->getListPhong($request->loaiPhong,$request->khoaId);
        return $data;
    }
    
    public function getNhomPhong(Request $request)
    {
        $data = $this->phongService->getNhomPhong($request->loaiPhong,$request->khoaId);
        return $data;
    }
    
    public function getListYeuCauKham(Request $request)
    {
        $data = $this->danhMucDichVuService->getListYeuCauKham($request);
        return $data;
    }
    
    public function getListNgheNghiep()
    {
        $data = $this->danhMucTongHopService->getListNgheNghiep();
        return $data;
    }
    
    public function danhMucBenhVien()
    {
        $data = $this->danhMucBenhVienService->getDanhMucBenhVien();
        return $data;
    }
    
    public function getListDanToc()
    {
        $data = $this->danhMucTongHopService->getListDanToc();
        return $data;
    }
    
    public function getListQuocTich()
    {
        $data = $this->danhMucTongHopService->getListQuocTich();
        return $data;
    }
    
    public function getListTinh()
    {
        $data = $this->danhMucTongHopService->getListTinh();
        return $data;
    }
    
    public function getListHuyen(Request $request)
    {
        $data = $this->danhMucTongHopService->getListHuyen($request->maTinh);
        return $data;
    }
    
    public function getListXa(Request $request)
    {
        $data = $this->danhMucTongHopService->getListXa($request->maHuyen,$request->maTinh);
        return $data;
    }
    
    public function benhVien()
    {
        $data = $this->benhVienService->listBenhVien();
        return $data;
    }
    
    public function getListLoaiVienPhi()
    {
        $data = $this->danhMucTrangThaiService->getListLoaiVienPhi();
        return $data;
    }
    
    public function getListDoiTuongBenhNhan()
    {
        $data = $this->danhMucTrangThaiService->getListDoiTuongBenhNhan();
        return $data;
    }
    
    public function getListKetQuaDieuTri()
    {
        $data = $this->danhMucTrangThaiService->getListKetQuaDieuTri();
        return $data;
    }
    
    public function getListGiaiPhauBenh()
    {
        $data = $this->danhMucTrangThaiService->getListGiaiPhauBenh();
        return $data;
    }
    
    public function getListXuTri()
    {
        $data = $this->danhMucTrangThaiService->getListXuTri();
        return $data;
    }
}