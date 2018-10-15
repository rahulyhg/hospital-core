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
        DanhMucDichVuService $danhmucdichvuService,
        DanhMucTongHopService $danhmuctonghopService
        )
    {
        $this->PhongService = $phongService;
        $this->DanhMucDichVuService = $danhmucdichvuService;
        $this->DanhMucTongHopService = $danhmuctonghopService;
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
        $data = $this->PhongService->getListPhong($request->loaiphong,$request->khoaid);
        return $data;
    }
    
    public function getListYeuCauKham(Request $request)
    {
        $data = $this->DanhMucDichVuService->getListYeuCauKham($request);
        return $data;
    }
    
    public function getListNgheNghiep()
    {
        $data = $this->DanhMucTongHopService->getListNgheNghiep();
        return $data;
    }
    
    public function getListBenhVien()
    {
        $data = $this->DanhMucTongHopService->getListBenhVien();
        return $data;
    }
    
    public function getListDanToc()
    {
        $data = $this->DanhMucTongHopService->getListDanToc();
        return $data;
    }
    
    public function getListQuocTich()
    {
        $data = $this->DanhMucTongHopService->getListQuocTich();
        return $data;
    }
    
    public function getListTinh()
    {
        $data = $this->DanhMucTongHopService->getListTinh();
        return $data;
    }
    
    public function getListHuyen(Request $request)
    {
        $data = $this->DanhMucTongHopService->getListHuyen($request->matinh);
        return $data;
    }
    
    public function getListXa(Request $request)
    {
        $data = $this->DanhMucTongHopService->getListXa($request->mahuyen,$request->matinh);
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