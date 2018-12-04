<?php
namespace App\Repositories\YLenh;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\YLenh;
use Carbon\Carbon;

class YLenhRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return YLenh::class;
    }
    
    public function createDataYLenh(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function saveYLenh(array $input)
    {
        $this->model->insert($input);
    }
    
    public function getLichSuYLenh(array $input)
    {
        $where = [
            ['y_lenh.khoa_id', '=', $input['khoa_id']],
            ['y_lenh.phong_id', '=', $input['phong_id']]
        ];
        
        $column = [
            'y_lenh.id',
            'y_lenh.ten',
            'y_lenh.ten_nhan_dan',
            'y_lenh.ten_bhyt',
            'y_lenh.ten_nuoc_ngoai',
            'y_lenh.loai_y_lenh',
            'y_lenh.thoi_gian_chi_dinh'
        ];
        
        $data = $this->model->where($where)
                            ->join('phieu_y_lenh', function($join) use ($input) {
                                $join->on('phieu_y_lenh.id', '=', 'y_lenh.phieu_y_lenh_id')
                                    ->where('phieu_y_lenh.dieu_tri_id', '=', $input['dieu_tri_id']);
                            })
                            ->orderBy('y_lenh.id')
                            ->get($column);
        
        if($data) {
            $itemXetNghiem = 0;
            $itemChanDoanHinhAnh = 0;
            $itemChuyenKhoa = 0;
            $array = [];
            $type = null;
            
            foreach($data as $item) {
                if($item->loai_y_lenh == 2) {
                    $itemXetNghiem++;
                    $type = 'XÉT NGHIỆM';
                }
                if($item->loai_y_lenh == 3) {
                    $itemChanDoanHinhAnh++;
                    $type = 'CHẨN ĐOÁN HÌNH ẢNH';
                }
                if($item->loai_y_lenh == 4) {
                    $itemChuyenKhoa++;
                    $type = 'CHUYÊN KHOA';
                }
                    
                $date = Carbon::parse($item->thoi_gian_chi_dinh)->format('d/m/Y');
                $item['key'] = $item->id;
                $array[$date][$type][] = $item;
            }
            
            $result['itemXetNghiem'] = $itemXetNghiem;
            $result['itemChanDoanHinhAnh'] = $itemChanDoanHinhAnh;
            $result['itemChuyenKhoa'] = $itemChuyenKhoa;
            $result['data'] = $array;
            
            return $result;
        } else {
            return null;
        }
    }
}