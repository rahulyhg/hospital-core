<?php

namespace App\Repositories\MedicalRecord;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Medicalrecord;
use Carbon\Carbon;

class MedicalRecordRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return Medicalrecord::class;
    }
    
    public function getInfoPatientByPatientID($patientid)
    {
        $column = [
            'hosobenhan.patientcode',
            'hosobenhan.patientname',
            //'hosobenhan.birthday',
            'hosobenhan.birthday_year',
            'hosobenhan.bhytcode',
            //'medicalrecord.thoigianvaovien'
        ];
        
        $data = DB::table('medicalrecord')
                ->join('hosobenhan', 'hosobenhan.hosobenhanid', '=', 'medicalrecord.hosobenhanid')
                ->where('medicalrecord.patientid', '=', $patientid)
                ->get($column);
        
        return $data;
    }
}