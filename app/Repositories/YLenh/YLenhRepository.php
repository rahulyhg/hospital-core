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
    
    public function getDetailPhieuYLenh($phieuYLenhId,$type)
    {
        $result = $this->model
                ->where('phieu_y_lenh_id',$phieuYLenhId)
                ->where('loai_y_lenh',$type)
                ->orderBy('id')
                ->get();
        if($result){
            foreach($result as $item){
                $phongThucHienId = DB::table('danh_muc_dich_vu')->where('ma',$item->ma)->first();
                if($phongThucHienId->phong_thuc_hien){
                    $phongThucHienName = DB::table('phong')->where('id',$phongThucHienId->phong_thuc_hien)->first();
                    $item['phong_thuc_hien']=$phongThucHienName?$phongThucHienName->ten_phong:'';
                }
                $item['children']=[[
                            'id'            => 'C'.$item->id,
                            'ten'           => 'Tên xét nghiệm '.$item->id,
                            'ket_qua'       => 'Kết quả xét nghiệm',
                            'don_vi'        => 'Đơn vị',
                            'gh_duoi'       => 'Giới hạn dưới',
                            'gh_tren'       => 'Giới hạn trên',
                            'ghi_chu_cd'    => 'Ghi chú chẩn đoán',
                            'ghi_chu_kq'    => 'Ghi chú kết quả' 
                ]];
            }
            return $result;
        }
        else
            return null;
    }     
}
