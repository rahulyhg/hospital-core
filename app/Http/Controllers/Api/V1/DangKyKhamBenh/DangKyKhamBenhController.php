<?php

namespace App\Http\Controllers\Api\V1\DangKyKhamBenh;

use Illuminate\Http\Request;
use App\Services\PhongService;
use App\Services\DanhMucDichVuService;
use App\Services\DanhMucTongHopService;
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
        DanhMucTongHopService $danhMucTongHopService
        )
    {
        $this->phongService = $phongService;
        $this->danhMucDichVuService = $danhMucDichVuService;
        $this->danhMucTongHopService = $danhMucTongHopService;
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
    
    public function getListBenhVien()
    {
        $data = $this->danhMucTongHopService->getListBenhVien();
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