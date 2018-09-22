<?php

namespace App\Http\Controllers\Api\V1\DonTiep;

use App\Http\Controllers\Controller;
use App\Services\HosobenhanService;
use Illuminate\Http\Request;

class HosobenhanController extends Controller
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
    public function getHosobenhan($patientid)
    {
        $data = $this->service->getHosobenhanByPatientID($patientid);
        return $data;
    }
}
