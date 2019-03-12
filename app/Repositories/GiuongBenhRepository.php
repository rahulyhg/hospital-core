<?php
namespace App\Repositories;

use App\Repositories\BaseRepositoryV2;
use App\Models\GiuongBenh;

class GiuongBenhRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return GiuongBenh::class;
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
    
    public function deleteByRoomId($id) {
        $this->model->where('phong_id', $id)->delete();
    }
    
    public function getGiuongBenhChuaSuDungByPhong($phongId) {
        $column = [
            'giuong_benh.id',
            'giuong_benh.stt',
        ];
        
        $where = [
            ['giuong_benh.phong_id', '=', $phongId],
            ['giuong_benh.tinh_trang', '=', 0]
        ];
        
        $result = $this->model->where($where)->get($column);
        return $result;
    }
}