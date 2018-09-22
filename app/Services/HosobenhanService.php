<?php

namespace App\Services;
use App\Http\Resources\HosobenhanResource;
use App\Http\Resources\PatientResource;
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
    
    public function getHosobenhanByPatientID($patientid){
        $data = $this->HosobenhanRepository->getHosobenhanByPatientID($patientid);
         
        return new HosobenhanResource($data);
    }
    
    public function getListBN_HC($start_day, $end_day, $offset, $limit){
        $data = $this->HosobenhanRepository->getListBN_HC($start_day, $end_day, $offset, $limit);
        
        return $data;
    }
    
    public function getListBN_PK($departmentid, $start_day, $end_day, $offset, $limit){
        $data = $this->HosobenhanRepository->getListBN_PK($departmentid, $start_day, $end_day, $offset, $limit);
        
        return $data;
    }
}