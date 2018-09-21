<?php

namespace App\Services;
use App\Http\Resources\PatientResource;
use App\Http\Resources\HosobenhanResource;
//use App\Repositories\Patient\PatientRepositoryInterface;
use App\Repositories\Patient\PatientRepository;
use Illuminate\Http\Request;
use Validator;

class PatientService{
    public function __construct(PatientRepository $PatientRepository)
    {
        $this->PatientRepository = $PatientRepository;
    }
    
    public function getDataPatient(Request $request)
    {
        $offset = $request->query('offset',0);
        //return array('result' => 'success');
        $Patient = $this->PatientRepository->getAll();
        return PatientResource::collection(
           //$this->repository->getForDataTable($offset)
           $Patient
        );
    }
    
    public function getDataPatientByStt($stt, $id_phong, $id_benh_vien)
    {
        
    }
   
}