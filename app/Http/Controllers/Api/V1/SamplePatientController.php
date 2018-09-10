<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\SamplePatientService;

class SamplePatientController extends APIController
{
    protected $repository;
    /**
     * __construct.
     *
     * @param $repository
     */
    public function __construct(SamplePatientService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Return the SamplePatient.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->service->getDataPatient($request);
        
        return $data;
    }
    
    /**
     * Return the specified resource.
     *
     * @param SamplePatient $patient
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $patient = $this->service->showPatient($id);
        
        return $patient;
    }
    
    /**
     * Creates the Resource for SamplePatient.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $patient = $this->service->makePatient($request);
        
        return $patient;
    }
    
    /**
     * Update SamplePatient.
     *
     * @param SamplePatient     $patient
     * @param Request           $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $patient = $this->service->updatePatient($request, $id);
        
        return $patient;
    }
    /**
     * Delete SamplePatient.
     *
     * @param SamplePatient     $patient
     * @param Request           $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $message = $this->service->deletePatient($id);
        
        return $message;
    }
    
}