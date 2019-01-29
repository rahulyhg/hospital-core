<?php

namespace App\Http\Controllers\Api\V1\DangKyKhamBenh;

use Illuminate\Http\Request;
use App\Services\PhongService;
use App\Services\KhoaService;
use App\Services\DanhMucDichVuService;
use App\Services\DanhMucTongHopService;
use App\Services\DanhMucBenhVienService;
use App\Services\DanhMucTrangThaiService;
use App\Services\BenhVienService;
use App\Services\HsbaKhoaPhongService;
use App\Services\HanhChinhService;
use App\Services\Icd10Service;
use App\Services\BhytService;
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
        KhoaService $khoaService, 
        DanhMucDichVuService $danhMucDichVuService,
        DanhMucTongHopService $danhMucTongHopService,
        DanhMucBenhVienService $danhMucBenhVienService,
        BenhVienService $benhVienService,
        DanhMucTrangThaiService $danhMucTrangThaiService,
        HsbaKhoaPhongService $hsbaKhoaPhongService,
        Icd10Service $icd10Service,
        BhytService $bhytService,
        HanhChinhService $hanhChinhService
        )
    {
        $this->phongService = $phongService;
        $this->khoaService = $khoaService;
        $this->danhMucDichVuService = $danhMucDichVuService;
        $this->danhMucTongHopService = $danhMucTongHopService;
        $this->danhMucBenhVienService = $danhMucBenhVienService;
        $this->benhVienService = $benhVienService;
        $this->danhMucTrangThaiService = $danhMucTrangThaiService;
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
        $this->icd10Service = $icd10Service;
        $this->bhytService = $bhytService;
        $this->hanhChinhService = $hanhChinhService;
        
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
        $data = $this->hanhChinhService->getListTinh();
        return $data;
    }
    
    public function getListHuyen(Request $request)
    {
        $data = $this->hanhChinhService->getListHuyen($request->maTinh);
        return $data;
    }
    
    public function getListXa(Request $request)
    {
        $data = $this->hanhChinhService->getListXa($request->maHuyen,$request->maTinh);
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
    
    public function getListKhoa($loaiKhoa, $benhVienId)
    {
        $data = $this->khoaService->getListKhoa($loaiKhoa, $benhVienId);
        return $data;
    }
    
    public function listKhoaByBenhVienId($benhVienId)
    {
        $data = $this->khoaService->listKhoaByBenhVienId($benhVienId);
        return $data;
    }    
    
    public function getLichSuKhamDieuTriByBenhNhanId(Request $request)
    {
        $data = $this->hsbaKhoaPhongService->getLichSuKhamDieuTri($request->benhNhanId);
        return $this->respond($data);
    }
    
    public function getListIcd10ByCode(Request $request)
    {
        $data = $this->icd10Service->getListIcd10ByCode($request->icd10Code);
        return $data;
    }
    
    public function getBhytTreEm(Request $request)
    {
        $data = $this->bhytService->getMaBhytTreEm($request->maTinh);
        return $data;
    }
    
    public function getThxByKey(Request $request)
    {
        $data = $this->hanhChinhService->getThxByKey($request->thxKey);
        return $data;
    }    
    
    public function getListHinhThucChuyen()
    {
        $data = $this->danhMucTrangThaiService->getListHinhThucChuyen();
        return $data;
    }
    
    public function getListTuyen()
    {
        $data = $this->danhMucTrangThaiService->getListTuyen();
        return $data;
    }
    
    public function getListLyDoChuyen()
    {
        $data = $this->danhMucTrangThaiService->getListLyDoChuyen();
        return $data;
    }
}