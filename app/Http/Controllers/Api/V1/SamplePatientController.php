<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\SamplePatientResource;
use App\Repositories\SamplePatientRepository;
use Illuminate\Http\Request;
use Validator;

class SamplePatientController extends APIController
{
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
    
    /**
     * Return the blogs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $offset = $request->query('offset',0);
        
        //return array('result' => 'success');
        return SamplePatientResource::collection(
           $this->repository->getForDataTable($offset)
        );
    }
}