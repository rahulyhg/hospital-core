<?php
namespace App\Repositories\DanhMuc;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\DanhMucTrangThai;


class DanhMucTrangThaiRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return DanhMucTrangThai::class;
    }
    
    public function getListDanhMucTrangThai($limit = 100, $page = 1)
    {
        $offset = ($page - 1) * $limit;
            
        $totalRecord = $this->model->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $this->model->limit($limit)
                    ->offset($offset)
                    ->orderBy('khoa','asc')
                    ->orderBy(DB::raw('LENGTH(gia_tri), gia_tri'))
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
    
    public function getListDanhMucTrangThaiByKhoa($khoa) {
        $dataSet = $this->model->where('khoa',$khoa)->get();
        return $dataSet;
        
    }
    
    public function createDanhMucTrangThai(array $input)
    {
        $id = 0;
        $composite_unique_count = $this->model->where([
            ['khoa','=',$input['khoa']], 
            ['gia_tri','=',$input['gia_tri']]
            ])->count();
        if($composite_unique_count == 0) $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function updateDanhMucTrangThai($dmttId, array $input)
    {
        $dmtt = $this->model->findOrFail($dmttId);
        $composite_unique_count = $this->model->where([
            ['id','!=',$dmttId], 
            ['khoa','=',$input['khoa']], 
            ['gia_tri','=',$input['gia_tri']]
            ])->count();
        $result = [];
		if($composite_unique_count == 0) $result = $dmtt->update($input);
		return $result;
    }
    
    public function deleteDanhMucTrangThai($dmttId)
    {
        $this->model->destroy($dmttId);
    }
    
    public function getDanhMucTrangThaiById($id) {
        $data = $this->model->where('id',$id)->first();
        return $data;    
        
    }
}