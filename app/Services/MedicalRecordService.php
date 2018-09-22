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
    
    public function getListBN_HC($start_day, $end_day, $offset, $limit){
        $data = $this->MedicalRecordRepository->getListBN_HC($start_day, $end_day, $offset, $limit);
        
        return $data;
    }
    
    public function getListBN_PK($departmentid, $start_day, $end_day, $offset, $limit){
        $data = $this->MedicalRecordRepository->getListBN_PK($departmentid, $start_day, $end_day, $offset, $limit);
        
        return $data;
    }
   
    
}