<?php
namespace App\Repositories\YLenh;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\YLenh;
use Carbon\Carbon;

class YLenhRepository extends BaseRepositoryV2
{
    const Y_LENH_YEU_CAU_KHAM = 'YÊU CẦU KHÁM';
    const Y_LENH_XET_NGHIEM = 'XÉT NGHIỆM';
    const Y_LENH_CHAN_DOAN_HINH_ANH = 'CHẨN ĐOÁN HÌNH ẢNH';
    const Y_LENH_CHUYEN_KHOA = 'CHUYÊN KHOA';
    
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
        $column = [
            'y_lenh.id',
            'y_lenh.ten',
            // 'y_lenh.ten_nhan_dan',
            // 'y_lenh.ten_bhyt',
            // 'y_lenh.ten_nuoc_ngoai',
            'y_lenh.loai_y_lenh',
            // 'y_lenh.thoi_gian_chi_dinh',
            'phong.ten_phong'
        ];
        
        $data = $this->model->join('phieu_y_lenh', function($join) use ($input) {
                                $join->on('phieu_y_lenh.id', '=', 'y_lenh.phieu_y_lenh_id')
                                    ->where('phieu_y_lenh.benh_nhan_id', '=', $input['benh_nhan_id'])
                                    ->where('phieu_y_lenh.hsba_id', '=', $input['hsba_id']);
                                    // ->whereNotNull('thoi_gian_chi_dinh');
                            })
                            ->leftJoin('phong', 'phong.id', '=', 'y_lenh.phong_id')
                            ->orderBy('y_lenh.id')
                            ->get($column);
        
        if($data) {
            $itemXetNghiem = 0;
            $itemChanDoanHinhAnh = 0;
            $itemChuyenKhoa = 0;
            $array = [];
            $type = null;
            
            foreach($data as $item) {
                $type = self::Y_LENH_YEU_CAU_KHAM;
                
                if($item->loai_y_lenh == 2) {
                    $itemXetNghiem++;
                    $type = self::Y_LENH_XET_NGHIEM;
                }
                if($item->loai_y_lenh == 3) {
                    $itemChanDoanHinhAnh++;
                    $type = self::Y_LENH_CHAN_DOAN_HINH_ANH;
                }
                if($item->loai_y_lenh == 4) {
                    $itemChuyenKhoa++;
                    $type = self::Y_LENH_CHUYEN_KHOA;
                }
                    
                // $date = Carbon::parse($item->thoi_gian_chi_dinh)->format('d/m/Y');
                $phong = $item->ten_phong;
                $item['key'] = $item->id;
                $array[$phong][$type][] = $item;
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