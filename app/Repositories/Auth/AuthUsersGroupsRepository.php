<?php
namespace App\Repositories\Auth;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Auth\AuthUsersGroups;

class AuthUsersGroupsRepository extends BaseRepositoryV2
{
   
    public function getModel()
    {
        return AuthUsersGroups::class;
    }    

     public function getIdGroupbyId($id)
    {
        $dataSet = $this->model->where('user_id',$id)->get();
        if($dataSet)
        {
            $result= array();
            foreach($dataSet as $dataset)
            {
                $result[] = $dataset->group_id;
            }
            return $result;
        }
    }

     public function getKhoaPhongByUserId($id,$benhVienId)
    {
        $column=['auth_groups.meta_data'];
        $dataSet = $this->model
                    ->leftJoin('auth_groups','auth_groups.id','=','auth_users_groups.group_id')
                    ->where([
                        ['auth_users_groups.user_id','=',$id],
                        ['auth_users_groups.benh_vien_id','=',$benhVienId],
                        ['auth_groups.meta_data','<>',null]
                    ])
                    ->get($column);
        $str1 = $dataSet->implode('meta_data', ',');
        $str2 = str_replace('[','',$str1);
        $str3 = str_replace(']','',$str2);
        $phongIdArray = array_map('intval', explode(',', $str3));;

        if($phongIdArray){
            $column=[
                'phong.id',
                'phong.khoa_id',
                'phong.ten_phong',
                'phong.ma_nhom',
                'khoa.ten_khoa'
                ];
            $result = DB::table('phong')
                    ->whereIn('phong.id',$phongIdArray)
                    ->leftJoin('khoa','khoa.id','=','phong.khoa_id')
                    ->orderBy('khoa.ten_khoa')
                    ->get($column);
            return $result;
            
        }
    }
    
    public function updateAuthUsersGroups($id,array $input)
    {
        $query = $this->model;
        $find = $query->where([['user_id','=',$id],['benh_vien_id','=',$input[0]['benh_vien_id']]])->first();
        if($find){
            $query->where([['user_id','=',$id],['benh_vien_id','=',$input[0]['benh_vien_id']]])->delete();
            foreach($input as $item){
                $query->insert(array(
                        'group_id'=>$item['id'],
                        'user_id'=>$id,
                        'benh_vien_id'=>$item['benh_vien_id']
                    ));
            }
        }
        else {
            foreach($input as $item){
                $query->insert(array(
                        'group_id'=>$item['id'],
                        'user_id'=>$id,
                        'benh_vien_id'=>$item['benh_vien_id']
                    ));
            }
        }
    }
    
    public function getAuthGroupsByUsersId($id,$benhVienId)
    {
        $column=[
                'auth_groups.*'
                ];
        $result = $this->model->leftJoin('auth_groups','auth_groups.id','=','auth_users_groups.group_id')->where([['auth_users_groups.user_id','=',$id],['auth_users_groups.benh_vien_id','=',$benhVienId]])->distinct()->get($column);
        return $result;
    }    
    
    
}