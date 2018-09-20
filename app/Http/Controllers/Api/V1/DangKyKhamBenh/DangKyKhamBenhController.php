<?php

namespace App\Http\Controllers\Api\V1\DangKyKhamBenh;

use Illuminate\Http\Request;
use App\Services\DangKyKhamBenhService;
use App\Services\DepartmentService;
use App\Services\ServicepricerefService;
use App\Http\Controllers\API\V1\APIController;

class DangKyKhamBenhController extends APIController
{
    /**
     * __construct.
     *
     * @param DangKyKhamBenhService $service
     */
    public function __construct(DangKyKhamBenhService $service , DepartmentService $DepartmentService, ServicepricerefService $servicepricerefService)
    {
        $this->service = $service;
        $this->departmentService = $DepartmentService;
        $this->servicepricerefService = $servicepricerefService;
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