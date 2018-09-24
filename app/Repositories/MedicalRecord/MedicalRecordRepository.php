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
            'medicalrecord.medicalrecordcode',
            'medicalrecord.canlamsangstatus',
            'tt1.diengiai as canlamsang_name',
            'medicalrecord.medicalrecordstatus',
            'tt2.diengiai as medicalrecord_name'
        ];
        
        if($start_day == $end_day){
            $data = DB::table('medicalrecord')
                ->join('hosobenhan', 'hosobenhan.hosobenhanid', '=', 'medicalrecord.hosobenhanid')
                ->leftJoin('red_trangthai as tt1', function($join) {
                    $join->on('tt1.giatri', '=', 'medicalrecord.canlamsangstatus')
                        ->where('tt1.tablename', '=', 'canlamsang');
                })
                ->leftJoin('red_trangthai as tt2', function($join) {
                    $join->on('tt2.giatri', '=', 'medicalrecord.medicalrecordstatus')
                        ->where('tt2.tablename', '=', 'patientstatus');
                })
                ->where($where)
                ->whereDate('thoigianvaovien', '=', $start_day)
                ->orderBy('thoigianvaovien', 'asc')
                ->offset($offset)
                ->limit($limit)
                ->get($column);
        } else {
            $data = DB::table('medicalrecord')
                ->join('hosobenhan', 'hosobenhan.hosobenhanid', '=', 'medicalrecord.hosobenhanid')
                ->leftJoin('red_trangthai as tt1', function($join) {
                    $join->on('tt1.giatri', '=', 'medicalrecord.canlamsangstatus')
                        ->where('tt1.tablename', '=', 'canlamsang');
                })
                ->leftJoin('red_trangthai as tt2', function($join) {
                    $join->on('tt2.giatri', '=', 'medicalrecord.medicalrecordstatus')
                        ->where('tt2.tablename', '=', 'patientstatus');
                })
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
            'medicalrecord.medicalrecordcode',
            'medicalrecord.canlamsangstatus',
            'tt1.diengiai as canlamsang_name',
            'medicalrecord.medicalrecordstatus',
            'tt2.diengiai as medicalrecord_name'
        ];
        
        $data = DB::table('medicalrecord')
                ->join('hosobenhan', 'hosobenhan.hosobenhanid', '=', 'medicalrecord.hosobenhanid')
                ->leftJoin('red_trangthai as tt1', function($join) {
                    $join->on('tt1.giatri', '=', 'medicalrecord.canlamsangstatus')
                        ->where('tt1.tablename', '=', 'canlamsang');
                })
                ->leftJoin('red_trangthai as tt2', function($join) {
                    $join->on('tt2.giatri', '=', 'medicalrecord.medicalrecordstatus')
                        ->where('tt2.tablename', '=', 'patientstatus');
                })
                ->where($where)
                ->whereDate('thoigianvaovien', '=', $start_day)
                ->orderBy('thoigianvaovien', 'asc')
                ->offset($offset)
                ->limit($limit)
                ->get($column);
        
        return $data;
    }
    
    
}