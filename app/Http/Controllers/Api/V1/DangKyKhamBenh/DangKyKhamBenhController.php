<?php

namespace App\Http\Controllers\Api\V1\DangKyKhamBenh;

use Illuminate\Http\Request;
use App\Services\DepartmentService;
use App\Services\ServicepricerefService;
use App\Services\NghenghiepService;
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
        DepartmentService $DepartmentService, 
        ServicepricerefService $servicepricerefService,
        NghenghiepService $nghenghiepservice,
        BenhVienService $benhvienservice
        )
    {
        $this->departmentService = $DepartmentService;
        $this->servicepricerefService = $servicepricerefService;
        $this->nghenghiepservice = $nghenghiepservice;
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
    public function getListDepartment(Request $request)
    {
        $data = $this->departmentService->getListDepartment($request);
        return $data;
    }
    
    public function ListYeuCauKham(Request $request)
    {
        $data = $this->servicepricerefService->getListYeuCauKham($request);
        return $data;
    }
    
    public function GetListNgheNghiep(Request $request)
    {
        $data = $this->nghenghiepservice->getListNgheNghiep($request);
        return $data;
    }
    
    public function GetListBenhVien(Request $request)
    {
        $data = $this->benhvienservice->getListBenhVien($request);
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