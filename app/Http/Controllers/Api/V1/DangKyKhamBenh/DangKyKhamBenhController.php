<?php

namespace App\Http\Controllers\Api\V1\DangKyKhamBenh;

use Illuminate\Http\Request;
use App\Services\DepartmentService;
use App\Services\DanhMucDichVuService;
use App\Services\DanhMucTongHopService;
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
        DepartmentService $departmentservice, 
        DanhMucDichVuService $danhmucdichvuservice,
        DanhMucTongHopService $danhmuctonghopservice,
        BenhVienService $benhvienservice
        )
    {
        $this->departmentservice = $departmentservice;
        $this->danhmucdichvuservice = $danhmucdichvuservice;
        $this->danhmuctonghopservice = $danhmuctonghopservice;
        $this->benhvienservice = $benhvienservice;
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
    
    // get danh sach phong kham theo departmentgroupid va departmenttypy
    public function getListPhong(Request $request)
    {
        $data = $this->departmentservice->getListDepartment($request);
        return $data;
    }
    
    public function getListYeuCauKham(Request $request)
    {
        $data = $this->danhmucdichvuservice->getListYeuCauKham($request);
        return $data;
    }
    
    public function getListNgheNghiep()
    {
        $data = $this->danhmuctonghopservice->getListNgheNghiep();
        return $data;
    }
    
    public function getListBenhVien()
    {
        $data = $this->danhmuctonghopservice->getListBenhVien();
        return $data;
    }
    
    public function getListDanToc()
    {
        $data = $this->danhmuctonghopservice->getListDanToc();
        return $data;
    }
    
    public function getListQuocTich()
    {
        $data = $this->danhmuctonghopservice->getListQuocTich();
        return $data;
    }
    
    public function getListTinh()
    {
        $data = $this->danhmuctonghopservice->getListTinh();
        return $data;
    }
    
    public function getListHuyen(Request $request)
    {
        $data = $this->danhmuctonghopservice->getListHuyen($request->matinh);
        return $data;
    }
    
    public function getListXa(Request $request)
    {
        $data = $this->danhmuctonghopservice->getListXa($request->mahuyen,$request->matinh);
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