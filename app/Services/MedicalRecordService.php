<?php

namespace App\Services;

use App\Models\Medicalrecord;
use App\Http\Resources\MedicalRecordResource;
use App\Repositories\MedicalRecord\MedicalRecordRepository;
use Illuminate\Http\Request;
use Validator;

class MedicalRecordService {
    public function __construct(MedicalRecordRepository $MedicalRecordRepository)
    {
        $this->MedicalRecordRepository = $MedicalRecordRepository;
    }
   
    public function getInfoPatientByPatientID($patientid){
        $data = $this->MedicalRecordRepository->getInfoPatientByPatientID($patientid);
         
        return $data;
    }
}