<?php
namespace App\Repositories;

use App\Repositories\BaseRepositoryV2;
use App\Models\PhongBenh;

class PhongBenhRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return PhongBenh::class;
    }
    
    public function getList($limit = 100, $page = 1, $keyWords = '')
    {
        $column = [
            'phong_benh.id',
            'phong_benh.ten',
            'phong_benh.loai_phong',
            'phong_benh.so_luong_giuong',
            'phong_benh.con_trong',
            'khoa.ten_khoa'
        ];
        
        $offset = ($page - 1) * $limit;
    
        $model = $this->model;
        
        $totalRecord = $model->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            $data = $model
                        ->leftJoin('khoa', 'khoa.id', '=', 'phong_benh.khoa_id')
                        ->where('ten', 'like', '%' . $keyWords . '%')
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
    
    public function getById($id)
    {
        $result = $this->model->find($id); 
        return $result;
    }
  
    public function create(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function update($id, array $input)
    {
        $data = $this->model->findOrFail($id);
		$data->update($input);
    }
    
    public function delete($id)
    {
        $this->model->where('id', $id)->delete();
    }
}