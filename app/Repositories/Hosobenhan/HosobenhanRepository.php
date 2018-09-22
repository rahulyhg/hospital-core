<?php
namespace App\Repositories\Hosobenhan;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Hosobenhan;

class HosobenhanRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return Hosobenhan::class;
    }
    
    public function getHosobenhanByPatientID($patientid)
    {
        $result = $this->model->where('patientid', $patientid)->first();
        
        return $result;
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
            'hosobenhan.patientcode',
            'hosobenhan.patientname',
            'hosobenhan.birthday_year',
            'hosobenhan.bhytcode',
            'hosobenhan.hosobenhanid',
            'medicalrecord.medicalrecordid',
            'medicalrecord.medicalrecordcode'
        ];
        
        if($start_day == $end_day){
            $data = DB::table('hosobenhan')
                ->join('medicalrecord', 'medicalrecord.hosobenhanid', '=', 'hosobenhan.hosobenhanid')
                ->where($where)
                ->whereDate('medicalrecord.thoigianvaovien', '=', $start_day)
                ->orderBy('medicalrecord.thoigianvaovien', 'asc')
                ->offset($offset)
                ->limit($limit)
                ->get($column);
        } else {
            $data = DB::table('hosobenhan')
                ->join('medicalrecord', 'medicalrecord.hosobenhanid', '=', 'hosobenhan.hosobenhanid')
                ->where($where)
                ->whereBetween('thoigianvaovien', [$start_day, $end_day])
                ->orderBy('thoigianvaovien', 'asc')
                ->offset($offset)
                ->limit($limit)
                ->get($column);
        }
        
        return $data;
    }
    
    public function getListBN_PK($departmentid, $start_day, $end_day, $offset, $limit = 10)
    {
        $loaibenhanid = 24; //kham benh
        
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
        
        $data = DB::table('hosobenhan')
                ->join('medicalrecord', 'medicalrecord.hosobenhanid', '=', 'hosobenhan.hosobenhanid')
                ->where($where)
                ->whereDate('thoigianvaovien', '=', $start_day)
                ->orderBy('thoigianvaovien', 'asc')
                ->offset($offset)
                ->limit($limit)
                ->get($column);
        
        return $data;
    }
}