<?php
namespace App\Repositories\YLenh;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\YLenh;
use Carbon\Carbon;

class YLenhRepository extends BaseRepositoryV2
{
    const Y_LENH_CODE_YEU_CAU_KHAM = 1;
    const Y_LENH_CODE_XET_NGHIEM = 2;
    const Y_LENH_CODE_CHAN_DOAN_HINH_ANH = 3;
    const Y_LENH_CODE_CHUYEN_KHOA = 4;
    
    const Y_LENH_TEXT_YEU_CAU_KHAM = 'YÊU CẦU KHÁM';
    const Y_LENH_TEXT_XET_NGHIEM = 'XÉT NGHIỆM';
    const Y_LENH_TEXT_CHAN_DOAN_HINH_ANH = 'CHẨN ĐOÁN HÌNH ẢNH';
    const Y_LENH_TEXT_CHUYEN_KHOA = 'CHUYÊN KHOA';
    
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
                if($item->loai_y_lenh == self::Y_LENH_CODE_YEU_CAU_KHAM) {
                    $type = self::Y_LENH_TEXT_YEU_CAU_KHAM;
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_XET_NGHIEM) {
                    $itemXetNghiem++;
                    $type = self::Y_LENH_TEXT_XET_NGHIEM;
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_CHAN_DOAN_HINH_ANH) {
                    $itemChanDoanHinhAnh++;
                    $type = self::Y_LENH_TEXT_CHAN_DOAN_HINH_ANH;
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_CHUYEN_KHOA) {
                    $itemChuyenKhoa++;
                    $type = self::Y_LENH_TEXT_CHUYEN_KHOA;
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
    
    public function getYLenhByHsbaId($hsbaId)
    {
        $total = 0;
        $result = [];
        $resultYeuCau = [];
        $column = [
            'y_lenh.id',
            'y_lenh.ten',
            'y_lenh.gia',
            'y_lenh.gia_bhyt_tra',
            'y_lenh.gia_mien_giam',
            'y_lenh.so_luong',
            'y_lenh.loai_y_lenh',
            'y_lenh.phieu_thu_id'
        ];
        
        $data = $this->model->join('phieu_y_lenh', function($join) use ($hsbaId) {
                                $join->on('phieu_y_lenh.id', '=', 'y_lenh.phieu_y_lenh_id')
                                    ->where('phieu_y_lenh.hsba_id', '=', $hsbaId);
                            })
                            ->orderBy('y_lenh.id')
                            ->get($column);
        if(!empty($data)) {
            $itemYeuCauKham = [];
            $itemXetNghiem = [];
            $itemChanDoanHinhAnh = [];
            $itemChuyenKhoa = [];
            
            $priceYeuCauKham = 0;
            $priceXetNghiem = 0;
            $priceChuanDoanHinhAnh = 0;
            $priceChuyenKhoa = 0;
            
            foreach($data as $item) {
                $item['gia']            = !empty($item['gia']) ? (int)$item['gia'] : 0;
                $item['gia_bhyt_tra']   = !empty($item['gia_bhyt_tra']) ? (int)$item['gia_bhyt_tra'] : 0;
                $item['gia_mien_giam']  = !empty($item['gia_mien_giam']) ? (int)$item['gia_mien_giam'] : 0;
                $item['so_luong']       = !empty($item['so_luong']) ? (int)$item['so_luong'] : 0;
                $item['thanh_tien'] = ($item['gia'] - $item['gia_bhyt_tra'] - $item['gia_mien_giam']) * $item['so_luong'];
                $item['key']        = rand() . $item['id'];
                
                if($item->loai_y_lenh == self::Y_LENH_CODE_YEU_CAU_KHAM) {
                    $itemYeuCauKham[] = $item;
                    $priceYeuCauKham += $item['thanh_tien'];
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_XET_NGHIEM) {
                    $itemXetNghiem[] = $item;
                    $priceXetNghiem += $item['thanh_tien'];
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_CHAN_DOAN_HINH_ANH) {
                    $itemChanDoanHinhAnh[] = $item;
                    $priceChuanDoanHinhAnh += $item['thanh_tien'];
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_CHUYEN_KHOA) {
                    $itemChuyenKhoa[] = $item;
                    $priceChuyenKhoa += $item['thanh_tien'];
                }
            }
            
            if(!empty($itemYeuCauKham)) {
                $result[] = [
                    'key'         => rand() . 'VP',
                    'ten'         => 'VIỆN PHÍ (' . number_format($priceYeuCauKham) . ' đ)',
                    'children'    => array([
                        'ten'           => 'Khám bệnh (' . number_format($priceYeuCauKham) . ' đ)',
                        'children'      => $itemYeuCauKham    
                    ])
                ];
                
                $total += $priceYeuCauKham;
            }
            
            if(!empty($itemXetNghiem)) {
                $resultYeuCau[] = [
                    'key'           => rand() . 'XN',
                    'ten'           => 'Xét nghiệm (' . number_format($priceXetNghiem) . ' đ)',
                    'children'      => $itemXetNghiem
                ]; 
            }
            
            if(!empty($itemChanDoanHinhAnh)) {
                $resultYeuCau[] = [
                    'key'           => rand() . 'CDHA',
                    'ten'           => 'Chuẩn đoán hình ảnh (' . number_format($priceChuanDoanHinhAnh) . ' đ)',
                    'children'      => $itemChanDoanHinhAnh
                ]; 
            }
            
            if(!empty($itemChuyenKhoa)) {
                $resultYeuCaup[] = [
                    'key'           => rand(). 'CK',
                    'ten'           => 'Chuyên khoa (' . number_format($priceChuyenKhoa) . ' đ)',
                    'children'      => $itemChuyenKhoa
                ]; 
            }
            
            if(!empty($resultYeuCau)) {
                $totalYeuCau = $priceXetNghiem + $priceChuanDoanHinhAnh + $priceChuyenKhoa;
                $result[] = [
                    'key'         => rand() . 'YC',
                    'ten'         => 'YÊU CẦU (' . number_format($totalYeuCau) . ' đ)',
                    'children'    => $resultYeuCau
                ];
                
                $total += $totalYeuCau;
            }
            
            $dataResult['data'] = $result;
            $dataResult['total'] = $total;
            
            return $dataResult;
        } else {
            return [];
        }
    }
    
    public function updatePhieuThuIdByHsbaId($hsbaId, array $input)
    {
        $column = [
            'y_lenh.id',
            'y_lenh.ten',
            'y_lenh.gia',
            'y_lenh.gia_bhyt_tra',
            'y_lenh.gia_mien_giam',
            'y_lenh.so_luong',
            'y_lenh.loai_y_lenh',
            'y_lenh.phieu_thu_id'
        ];
        
        $data = $this->model->join('phieu_y_lenh', function($join) use ($hsbaId) {
                        $join->on('phieu_y_lenh.id', '=', 'y_lenh.phieu_y_lenh_id')
                            ->where('phieu_y_lenh.hsba_id', '=', $hsbaId);
                    })
                    ->update(['phieu_thu_id' => $input['phieu_thu_id']]);
    }
}
