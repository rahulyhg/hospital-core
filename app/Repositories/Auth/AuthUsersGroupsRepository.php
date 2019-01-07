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

     public function getKhoaPhongByUserId($id)
    {
        // $dataSet = DB::table('auth_users_groups')
        //             ->where([
        //                 ['user_id','=',$id],
        //                 ['khoa_id','<>',null],
        //                 ['phong_id','<>',null],
        //                 ])
        //             ->get(['khoa_id','phong_id']);
        $column=['auth_groups.meta_data'];
        $dataSet = $this->model
                    ->leftJoin('auth_groups','auth_groups.id','=','auth_users_groups.group_id')
                    ->where([['auth_users_groups.user_id','=',$id],['auth_groups.meta_data','<>',null]])
                    ->get($column);
        $str1 = $dataSet->implode('meta_data', ',');
        $str2 = str_replace('[','',$str1);
        $str3 = str_replace(']','',$str2);
        $phongIdArray = explode(',',$str3);
        
        if($phongIdArray){
        //var_dump($phongIdArray);
            $column=[
                'phong.id',
                'phong.khoa_id',
                'phong.ten_phong',
                'khoa.ten_khoa'
                ];
            $result = DB::table('phong')
                    ->whereIn('phong.id',$phongIdArray)
                    ->leftJoin('khoa','khoa.id','=','phong.khoa_id')
                    ->orderBy('khoa.ten_khoa')
                    ->get($column);
            return $result;
            
        }
        // if(count($dataSet)>0)
        // {
        //     $phongId = $dataSet->implode('phong_id', ',');
        //     $khoaId = $dataSet->implode('khoa_id', ',');
        //     $phongIdArray = explode(",",$phongId);
        //     $khoaIdArray = explode(",",$khoaId);
        //     $column=[
        //         'phong.id',
        //         'phong.khoa_id',
        //         'phong.ten_phong',
        //         'khoa.ten_khoa'
        //         ];
        //     $result = DB::table('phong')
        //             ->whereIn('phong.id',$phongIdArray)
        //             ->whereIn('phong.khoa_id',$khoaIdArray)
        //             ->leftJoin('khoa','khoa.id','=','phong.khoa_id')
        //             ->get($column);
        //     return $result;
        // }
        
    }
    
    public function updateAuthUsersGroups($id,array $input)
    {
        $query = $this->model;
        $find = $query->where('user_id',$id)->first();
        if($find){
            $query->where('user_id',$id)->delete();
            foreach($input as $item){
                $query->insert(array(
                        'group_id'=>$item['id'],
                        'user_id'=>$id
                    ));
            }
        }
        else {
            foreach($input as $item){
                $query->insert(array(
                        'group_id'=>$item['id'],
                        'user_id'=>$id
                    ));
            }
        }
    }
    
    public function getAuthGroupsByUsersId($id)
    {
        $column=[
                'group_id',
                'user_id'
                ];
        $result = $this->model->where('user_id',$id)->distinct()->get($column);
        return $result;
    }    
    
    
}