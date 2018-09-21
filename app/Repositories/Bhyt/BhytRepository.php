<?php
namespace App\Repositories\Bhyt;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Bhyt;
use App\Models\Hosobenhan;
class BhytRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return Bhyt::class;
    }
     public function getTypePatientByCode($bhytcode)
    {
        //$patientid= '542368';
        //$result = \App\Models\Patient::find($patientid)->with('hosobenhan')->first();
        //$name = $result->patientid;
        $result = $this->model->where('bhytcode', $bhytcode)->first();
        //$patientid = $result['patientid'];
        //$hosobenhanid = $result['hosobenhanid'];
        //$patientname = \App\Models\Patient::find($result['patientid'])->first();
        $datapatient = Hosobenhan::find($result['hosobenhanid']);
        //return $result;
        return $datapatient;
    }
}