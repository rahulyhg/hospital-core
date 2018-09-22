<?php

namespace App\Http\Controllers\Api\V1\DonTiep;

use Illuminate\Http\Request;
use App\Services\RedSttDontiepService;
use App\Http\Controllers\Api\V1\APIController;

class RedSttDontiepController extends APIController
{
    /**
     * __construct.
     *
     * @param RedSttDontiepService $service
     */
    public function __construct(RedSttDontiepService $RedSttDontiepService)
    {
        $this->service = $RedSttDontiepService;
    }
    
    
    public function getCurrentSTTBT(){
        $data = $this->service->getCurrentSTTBT();
        return $data;
    }
    
    public function insertCurrentSTTBT(array $attributes){
        $this->service->insertCurrentSTTBT($attributes);
    }
    
    public function getCurrentSTTUT(){
        $data = $this->service->getCurrentSTTUT();
        return $data;
    }
    
    public function getSTTKM($age)
    {
        $data = $this->service->getSTTKM($age);
        return $data;
    }
    /**
     * Return the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //$patient = $this->service->showPatient($id);
        
        //return $patient;
    }
    
    /**
     * Creates the Resource for RedSttDontiep.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //$patient = $this->service->makePatient($request);
        
        //return $patient;
    }
    
    /**
     * Update RedSttDontiep.
     *
     * @param Request           $request
     * @param int               $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //$patient = $this->service->updatePatient($request, $id);
        
        //return $patient;
    }
    
}