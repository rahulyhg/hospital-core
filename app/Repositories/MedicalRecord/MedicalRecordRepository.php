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
    
    public function getListBN_HC($start_day, $end_day, $offset, $limit = 10)
    {
        $loaibenhanid = 24; //kham benh
        $departmentgroupid = 3; //khoa kham benh
        
        $where = [
            ['medicalrecord.loaibenhanid', '=', $loaibenhanid],
            ['medicalrecord.departmentgroupid', '=', $departmentgroupid],
        ];
        
        $column = [
            'hosobenhan.patientid',
            'hosobenhan.patientname',
            //'hosobenhan.birthday',
            'hosobenhan.birthday_year',
            'bhyt.bhytcode',
            //'medicalrecord.thoigianvaovien'
        ];
        
        if($start_day == $end_day){
            $data = DB::table('medicalrecord')
                ->join('hosobenhan', 'hosobenhan.hosobenhanid', '=', 'medicalrecord.hosobenhanid')
                ->join('bhyt', 'bhyt.bhytid', '=', 'medicalrecord.bhytid')
                ->where($where)
                ->whereDate('thoigianvaovien', '=', $start_day)
                ->orderBy('thoigianvaovien', 'asc')
                ->offset($offset)
                ->limit($limit)
                ->get($column);
        } else {
            $data = DB::table('medicalrecord')
                ->join('hosobenhan', 'hosobenhan.hosobenhanid', '=', 'medicalrecord.hosobenhanid')
                ->join('bhyt', 'bhyt.bhytid', '=', 'medicalrecord.bhytid')
                ->where($where)
                ->whereBetween('thoigianvaovien', [$start_day, $end_day])
                ->orderBy('thoigianvaovien', 'asc')
                ->offset($offset)
                ->limit($limit)
                ->get($column);
        }
        
        return $data;
    }
}