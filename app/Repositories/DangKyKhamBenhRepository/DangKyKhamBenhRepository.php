<?php
namespace App\Repositories\DangKyKhamBenhRepository;
use DB;
use App\Repositories\BaseRepository;

class DangKyKhamBenhRepository extends BaseRepository
{

     public function getDataListDepartment($offset , $request)
    {
        $department = DB::table('department')
                ->where([
                    'departmenttype'=>$request->departmenttype,
                    'departmentgroupid'=>$request->departmentgroupid
                ])
                ->offset($offset)
                ->orderBy('departmentname')
                ->get();
        return $department;    
    }
    
    public function getDataYeuCauKham($offset, $request)
    {
        $data = DB::table('servicepriceref')
                ->where('servicegrouptype',$request->servicegrouptype)
                ->offset($offset)
                ->orderBy('servicepricename')
                ->get();
        return $data;    
    }
    
}