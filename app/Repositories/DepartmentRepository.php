<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;

class DepartmentRepository extends BaseRepository
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
    
}