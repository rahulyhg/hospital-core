<?php

namespace App\Http\Controllers\Api\V1;
use App\Services\HosobenhanService;
use Illuminate\Http\Request;

class HosobenhanController extends APIController
{
    //
    protected $repository;
    /**
     * __construct.
     *
     * @param $service
     */
    public function __construct(HosobenhanService $service)
    {
        //$this->repository = $repository;
        $this->service = $service;
    }
    
    /**
     * Return the blogs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //$data = $this->service->getDataPatient($request);
        
        //return $data;
    }
    public function register(Request $request)
    {
        //return SamplePatientResource::create($request->all());
    }
    public function typePatient($patientid)
    {
        $data = $this->service->getTypePatient($patientid);
        return $data;
    }
}
