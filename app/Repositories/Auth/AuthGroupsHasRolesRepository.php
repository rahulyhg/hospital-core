<?php
namespace App\Repositories\Auth;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Auth\AuthGroupsHasRoles;

class AuthGroupsHasRolesRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return AuthGroupsHasRoles::class;
    }    

     public function getRolesbyIdGroup($idGroup)
    {
        $dataSet =  $this->model->whereIn('group_id',$idGroup)->get();
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
    
    public function updateAuthGroupsHasRoles($id,array $input)
    {
        $query = $this->model;
        $find = $query->where('group_id',$id)->first();
        if($find){
            $query->where('group_id',$id)->delete();
            foreach($input as $item){
                $query->insert(array(
                        'role_id'=>$item['id'],
                        'group_id'=>$id
                    ));
            }
        }
        else {
            foreach($input as $item){
                $query->insert(array(
                        'role_id'=>$item['id'],
                        'group_id'=>$id
                    ));
            }
        }
    }
    
    public function getRolesByGroupsId($id)
    {
        $column=[
                'auth_groups_has_roles.group_id',
                'auth_groups_has_roles.role_id',
                'auth_roles.name',
                'auth_roles.description'
                ];
        $result = $this->model
            ->where('group_id',$id)
            ->leftJoin('auth_roles','auth_roles.id','=','auth_groups_has_roles.role_id')
            ->distinct()
            ->get($column);
        return $result;
    }    
    
}