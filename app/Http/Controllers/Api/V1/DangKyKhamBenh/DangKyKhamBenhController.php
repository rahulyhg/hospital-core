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
    
    /**
     * Return the DangKyKhamBenh.
     * 
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function getData(Request $request)
    // {
    //     $data = $this->service->getDataPatient($request);
        
    //     return $data;
    // }
    
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
    /**
     * Return the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function show($id)
    // {
    //     $patient = $this->service->showPatient($id);
        
    //     return $patient;
    // }

    /**
     * Creates the Resource for DangKyKhamBenh.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function store(Request $request)
    // {
    //     $patient = $this->service->makePatient($request);
        
    //     return $patient;
    // }
    
    /**
     * Update DangKyKhamBenh.
     *
     * @param Request           $request
     * @param int               $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function update(Request $request, $id)
    // {
    //     $patient = $this->service->updatePatient($request, $id);
        
    //     return $patient;
    // }
    
    /**
     * Delete DangKyKhamBenh.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function delete($id)
    // {
    //     $message = $this->service->deletePatient($id);
        
    //     return $message;
    // }
    
}