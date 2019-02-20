<?php
namespace App\Repositories\DanhMuc;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\NhaCungCap;
use Carbon\Carbon;

class NhaCungCapRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return NhaCungCap::class;
    }    
    
    public function getListNhaCungCap($limit = 100, $page = 1, $keyWords ='')
    {
        $offset = ($page - 1) * $limit;
        
        $model = $this->model;

        if($keyWords!=''){
          $model->where('ten_nha_cung_cap', 'like', '%' . strtolower($keyWords) . '%');
        }
            
        $totalRecord = $model->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $model->orderBy('id', 'desc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
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
    
    public function createNhaCungCap(array $input)
    {
        if($input['trang_thai_su_dung']==true)
            $input['trang_thai_su_dung']=1;
        else
            $input['trang_thai_su_dung']=0;
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function updateNhaCungCap($id, array $input)
    {
        if($input['trang_thai_su_dung']==true)
            $input['trang_thai_su_dung']=1;
        else
            $input['trang_thai_su_dung']=0;        
        $find = $this->model->findOrFail($id);
		$find->update($input);
    }
    
    public function deleteNhaCungCap($id)
    {
        $this->model->destroy($id);
    }
    
    public function getNhaCungCapById($id)
    {
        $data = $this->model
                ->where('id', $id)
                ->first();
        return $data;
    }     
}