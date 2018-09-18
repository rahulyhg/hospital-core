<?php

namespace App\Services;
use App\Http\Resources\HosobenhanResource;
use App\Http\Resources\PatientResource;
//use App\Repositories\Patient\PatientRepositoryInterface;
use App\Repositories\Hosobenhan\HosobenhanRepository;
use Illuminate\Http\Request;
use Validator;

class HosobenhanService{
    /**
     * __construct.
     *
     * @param $HosobenhnhanRepository
     */
    public function __construct(HosobenhanRepository $HosobenhanRepository)
    {
        $this->HosobenhanRepository = $HosobenhanRepository;
    }
    public function getDataPatient(Request $request)
    {
        $offset = $request->query('offset',0);
        //return array('result' => 'success');
        //$Patient =$this->PatientRepository->getAll();
        //return PatientResource::collection(
           //$this->repository->getForDataTable($offset)
          // $Patient
        //);
    }
    /*public function getTypePatient($id)
    {
        $TypePatient = $this->HosobenhanRepository->getTypePatient($id);
         return HosobenhanResource::collection(
           //$this->repository->getForDataTable($offset)
           $TypePatient
        );
    }*/
     public function getTypePatient($patientid){
         $typepatient = $this->HosobenhanRepository->getTypePatient($patientid);
         return new HosobenhanResource($typepatient);
        //return $typepatient;
    }
    
}