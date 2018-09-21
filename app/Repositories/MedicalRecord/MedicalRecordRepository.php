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
    
    public function getListBN_HC($start_day, $end_day)
    {
        $loaibenhanid = 24; //kham benh
        $departmentgroupid = 3; //khoa kham benh
        
        if($start_day == $end_day){
            $dieu_kien = [
                ['loaibenhanid', '=', $loaibenhanid],
                ['departmentgroupid', '=', $departmentgroupid],
            ];
            
            $arr_hosobenhanid = DB::table('medicalrecord')
                ->where($dieu_kien)
                ->whereDate('thoigianvaovien', '=', $start_day)
                ->orderBy('thoigianvaovien', 'asc')
                ->pluck('hosobenhanid')
                ->toArray();
        } else {
            $dieu_kien = [
                ['loaibenhanid', '=', $loaibenhanid],
                ['departmentgroupid', '=', $departmentgroupid],
            ];
            
            $arr_hosobenhanid = DB::table('medicalrecord')
                ->where($dieu_kien)
                ->whereBetween('thoigianvaovien', [$start_day, $end_day])
                ->orderBy('thoigianvaovien', 'asc')
                ->pluck('hosobenhanid')
                ->toArray();
        }
        
        return $arr_hosobenhanid;
    }
}