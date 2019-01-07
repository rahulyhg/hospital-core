<?php
namespace App\Repositories\Auth;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Auth\AuthGroups;
use Carbon\Carbon;

class AuthGroupsRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return AuthGroups::class;
    }    
    
    public function getListAuthGroups($limit = 100, $page = 1, $keyWords ='')
    {
        $offset = ($page - 1) * $limit;
        $column = [
            'id',
            'name',
            'description'
        ];
        $query = DB::table('auth_groups');
        if($keyWords!=""){
           $query->where('name', 'like', '%' . $keyWords . '%')
                 ->orWhere('description', 'like', '%' . $keyWords . '%');
        }        
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('id', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
        } else {
            $totalPage = 0;
            $data = [];
            $page = 0;
            $totalRecord = 0;
        }
            
        $result = [
            'data'          => $data,
            'page'          => $page,
            'totalPage'     => $totalPage,
            'totalRecord'   => $totalRecord
        ];
        
        return $result;
    }
    
    public function getByListId($limit = 100,$page =1,$id)
    {
        $id = json_encode($id);
       
        $offset = ($page - 1) * $limit;
        
        $column = [
            'id',
            'name',
            'description'
        ];
        
        $query = $this->model;
            
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->whereIn('id',$id)
                        ->orderBy('id', 'desc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
        } else {
            $totalPage = 0;
            $data = [];
            $page = 0;
            $totalRecord = 0;
        }
            
        $result = [
            'data'          => $data,
            'page'          => $page,
            'totalPage'     => $totalPage,
            'totalRecord'   => $totalRecord
        ];
        
        return $result;        
    }
    
    public function createAuthGroups(array $input)
    {
        $input['description']=$input['ghi_chu'];    
        $id = $this->model->create($input)->id;
        return $id;
    }

    public function getAuthGroupsById($id)
    {
        $result = $this->model->where('id', $id)->first(); 
        return $result;
    }
    
    public function updateAuthGroups($id, array $input)
    {
        $arr = [];
        if($input['phongId']){
            foreach($input['phongId'] as $item){
                if(isset($item['phong_id'])){
                    $arr[]=$item['phong_id'];
                }
            }
        }
        $input['meta_data']=json_encode($arr);
        $input['description']=$input['ghi_chu'];
        $update = $this->model->findOrFail($id);
		$update->update($input);
    }
    
    public function getKhoaPhongByGroupsId($id)
    {
        $metaData = $this->model
            ->where('id', $id)
            ->get()
            ->first()
            ->meta_data;
        if($metaData){
        $result = DB::table('phong')
            ->select(DB::raw("CONCAT('0',khoa_id,id) AS key"),'id as phong_id','khoa_id','ten_phong as ten_khoa_phong')
            ->whereIn('id',json_decode($metaData))
            ->get();
        return $result;
        }
    }    
    
}