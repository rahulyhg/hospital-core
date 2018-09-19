<?php

namespace App\Http\Controllers\Api\V1\DonTiep;

//use App\Http\Resources\SamplePatientResource;
//use App\Repositories\SamplePatientRepository;
use App\Services\PatientService;
use Illuminate\Http\Request;
//use Validator;

class PatientController extends APIController
{
    protected $repository;
    /**
     * __construct.
     *
     * @param $service
     */
    public function __construct(PatientService $service)
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
        $data = $this->service->getDataPatient($request);
        
        return $data;
    }
    public function register(Request $request)
    {
        //return SamplePatientResource::create($request->all());
    }
    
}