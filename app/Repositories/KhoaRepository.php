<?php
namespace App\Repositories;

use DB;
use App\Models\Khoa;
use App\Repositories\BaseRepositoryV2;

class KhoaRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return Khoa::class;
    }
    
    public function getListKhoa($loaiKhoa, $benhVienId)
    {
        $data = $this->model->where([
                    'loai_khoa'     =>  $loaiKhoa,
                    'benh_vien_id'  =>  $benhVienId
                ])
                ->orderBy('ten_khoa')
                ->get();
        return $data;    
    }
    
    public function listKhoaByBenhVienId($benhVienId)
    {
        $data = $this->model->where([
                    'benh_vien_id'  =>  $benhVienId
                ])
                ->orderBy('ten_khoa')
                ->get();
        return $data;    
    }
    
    public function getTreeListKhoaPhong($limit = 100, $page = 1, $benhVienId)
    {
        $offset = ($page - 1) * $limit;
        
        $column = [
            'id as phong_id',
            'khoa_id',
            'ten_phong as ten_khoa_phong'
        ];
        
        $query = $this->model->where('benh_vien_id',$benhVienId);
        
        $dataSet = [];    
        
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('ten_khoa', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
            if($data){
                foreach($data as $item){
                    $dataPhong = DB::table('phong')
                            ->select(DB::raw("CONCAT('0',khoa_id,id) AS key"),'id as phong_id','khoa_id','ten_phong as ten_khoa_phong')
                            ->orderBy('ten_phong','asc')
                            ->where('khoa_id',$item->id)
                            ->get();
                    $dataSet[]=[
                        'key'               =>  $item->id,
                        'id'                =>  $item->id,
                        'ma_khoa'           =>  $item->ma_khoa,
                        'ten_khoa_phong'    =>  $item->ten_khoa,
                        'children'          =>  $dataPhong
                    ];
                }
            }
            
        } else {
            $totalPage = 0;
            $data = [];
            $page = 0;
            $totalRecord = 0;
        }
            
        $result = [
            'data'          => $dataSet,
            'page'          => $page,
            'totalPage'     => $totalPage,
            'totalRecord'   => $totalRecord
        ];
        
        return $result;
    }    
    
    public function createKhoa($benhVienId, array $input)
    {
        $input['benh_vien_id'] = $benhVienId;
        return $this->model->create($input)->id;
    }
}