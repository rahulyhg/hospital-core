<?php

namespace App\Services;
use App\Http\Resources\SamplePatientResource;
use App\Repositories\SamplePatientRepository;
use Illuminate\Http\Request;
use Validator;

class SamplePatientService{
    protected $repository;
    /**
     * __construct.
     *
     * @param $repository
     */
    public function __construct(SamplePatientRepository $repository)
    {
        $this->repository = $repository;
    }
    public function getDataPatient(Request $request)
    {
        $offset = $request->query('offset',0);
        //return array('result' => 'success');
        return SamplePatientResource::collection(
           $this->repository->getForDataTable($offset)
        );
    }
    
}