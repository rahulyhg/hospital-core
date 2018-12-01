<?php
namespace App\Models\Sqs;

class HsbaKhoaPhong extends BaseModel
{
    public $attributes = ['benh_vien_id','khoa_id','phong_id'];
    public $fields = [
        'benh_vien_id','hsba_id', 
        'hsba_khoa_phong_id', 'ten_benh_nhan', 'nam_sinh', 'ms_bhyt', 'trang_thai_hsba',
        'ngay_tao', 'ngay_ra_vien', 'thoi_gian_vao_vien', 'thoi_gian_ra_vien',
        'trang_thai_cls', 'ten_trang_thai_cls', 'trang_thai', 'ten_trang_thai'
        ];
    
    public $message;    
    public $body;
    
    public $validations = [];
    
}
