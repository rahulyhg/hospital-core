<?php
namespace App\Repositories\Hosobenhan;
use DB;
use App\Repositories\BaseRepositoryV2;
//use App\Models\Patient;

class HosobenhanRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return \App\Models\Hosobenhan::class;
    }
    //public function getTypePatient($id){
      //  $typepatient = \App\Models\Hosobenhan::find($id)->with('patient')->get();
      //  return $typepatient;
    //}
    public function getTypePatient($patientid)
    {
        //$patientid= '542368';
        //$result = \App\Models\Patient::find($patientid)->with('hosobenhan')->first();
        //$name = $result->patientid;
        $result = $this->model->where('patientid', $patientid)->first();

        
        return $result;
    }
}