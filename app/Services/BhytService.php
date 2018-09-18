<?php

namespace App\Services;
use App\Http\Resources\HosobenhanResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\BhytResource;
//use App\Repositories\Patient\PatientRepositoryInterface;
use App\Repositories\Bhyt\BhytRepository;
use Illuminate\Http\Request;
use Validator;
class BhytService{
    /**
     * __construct.
     *
     * @param $BhytRepository
     */
    public function __construct(BhytRepository $BhytRepository)
    {
        $this->BhytRepository = $BhytRepository;
    }
    public function getTypePatientByCode($bhytcode)
    {
        $datapatient = $this->BhytRepository->getTypePatientByCode($bhytcode);
        //return new BhytResource($datapatient);
        //$x = array(
        //        'patientid' => $datapatient['patientid'],
        //        'hosobenhanid' => $datapatient['hosobenhanid'],
        //);
        
        return new HosobenhanResource($datapatient);
        
         
         //$datapatientobj = new BhytResource($datapatient);
         //foreach($datapatientobj as $obj) {
             // $patientid = $obj['patientid'];
   
    }
}