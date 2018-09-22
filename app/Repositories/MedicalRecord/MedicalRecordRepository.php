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
    
    public function getListBN_HC($start_day, $end_day, $offset, $limit = 10, $patientname = '')
    {
        $loaibenhanid = 24; //kham benh
        $departmentgroupid = 3; //khoa kham benh
        
        if($patientname != '')
            $where = [
                ['medicalrecord.loaibenhanid', '=', $loaibenhanid],
                ['medicalrecord.departmentgroupid', '=', $departmentgroupid],
                ['hosobenhan.patientname', 'like', "%$patientname%"]
            ];
        else
            $where = [
                ['medicalrecord.loaibenhanid', '=', $loaibenhanid],
                ['medicalrecord.departmentgroupid', '=', $departmentgroupid],
            ];
        
        $column = [
            'hosobenhan.patientcode',
            'hosobenhan.patientname',
            'hosobenhan.birthday_year',
            'hosobenhan.bhytcode',
            'hosobenhan.hosobenhanid',
            'medicalrecord.medicalrecordid',
            'medicalrecord.medicalrecordcode'
        ];
        
        if($start_day == $end_day){
            $data = DB::table('medicalrecord')
                ->join('hosobenhan', 'hosobenhan.hosobenhanid', '=', 'medicalrecord.hosobenhanid')
                ->where($where)
                ->whereDate('thoigianvaovien', '=', $start_day)
                ->orderBy('thoigianvaovien', 'asc')
                ->offset($offset)
                ->limit($limit)
                ->get($column);
        } else {
            $data = DB::table('medicalrecord')
                ->join('hosobenhan', 'hosobenhan.hosobenhanid', '=', 'medicalrecord.hosobenhanid')
                ->where($where)
                ->whereBetween('thoigianvaovien', [$start_day, $end_day])
                ->orderBy('thoigianvaovien', 'asc')
                ->offset($offset)
                ->limit($limit)
                ->get($column);
        }
        
        return $data;
    }
    
    public function getListBN_PK($departmentid, $start_day, $end_day, $offset, $limit = 10, $patientname = '')
    {
        $loaibenhanid = 24; //kham benh
        
        if($patientname != '')
            $where = [
                ['medicalrecord.loaibenhanid', '=', $loaibenhanid],
                ['medicalrecord.departmentid', '=', $departmentid],
                ['hosobenhan.patientname', 'like', '%$patientname%']
            ];
        else
            $where = [
                ['medicalrecord.loaibenhanid', '=', $loaibenhanid],
                ['medicalrecord.departmentid', '=', $departmentid],
            ];
        
        $column = [
            'hosobenhan.patientcode',
            'hosobenhan.patientname',
            'hosobenhan.birthday_year',
            'hosobenhan.bhytcode',
            'hosobenhan.hosobenhanid',
            'medicalrecord.medicalrecordid',
            'medicalrecord.medicalrecordcode'
        ];
        
        $data = DB::table('medicalrecord')
                ->join('hosobenhan', 'hosobenhan.hosobenhanid', '=', 'medicalrecord.hosobenhanid')
                ->where($where)
                ->whereDate('thoigianvaovien', '=', $start_day)
                ->orderBy('thoigianvaovien', 'asc')
                ->offset($offset)
                ->limit($limit)
                ->get($column);
        
        return $data;
    }
    
    
}