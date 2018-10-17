<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;
use App\Models\Auth\AuthGroupsHasRoles;

class AuthGroupsHasRolesRepository extends BaseRepository
{

     public function getRolesbyIdGroup($idGroup)
    {
        $dataSet =  DB::table('auth_groups_has_roles')->whereIn('group_id',$idGroup)->get();
        if($dataSet)
        {
            $result= array();
            foreach($dataSet as $dataset)
            {
                $result[] =$dataset->role_id;
            }
            $result = array_values(array_unique($result));
            return $result;
        }
    }
    
}