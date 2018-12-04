<?php

namespace App\Models\ValueObjects;

class NhomNguoiThan
{
    private $data = [];
    
    const REQUIRED_SIZE = 3;
    
    public function __construct(array $nhomLoaiNguoiThan, array $nhomTenNguoiThan, array $nhomDTNguoiThan) {
        if (count($nhomLoaiNguoiThan)!=self::REQUIRED_SIZE || count($nhomTenNguoiThan)!=self::REQUIRED_SIZE || count($nhomDTNguoiThan)!=self::REQUIRED_SIZE) {
            
            // var_dump($nhomLoaiNguoiThan);
            // echo "<hr/>";
            // var_dump($nhomTenNguoiThan);
            // echo "<hr/>";
            // var_dump($nhomDTNguoiThan);
            // echo "<hr/>";
            
            throw new \InvalidArgumentException('Khởi tạo nhóm người thân không hợp lệ!');
        }   
        foreach ($nhomLoaiNguoiThan as $index => $loaiNguoiThan) {
            $tenNguoiThan = ($nhomTenNguoiThan[$index])?$nhomTenNguoiThan[$index]:null;
            $dTNguoiThan = ($nhomDTNguoiThan[$index])?$nhomDTNguoiThan[$index]:null;
            
            $dataNguoiThan = [
                'loai_nguoi_than' => $loaiNguoiThan,
                'ten_nguoi_than' => $tenNguoiThan,
                'dien_thoai_nguoi_than' => $dTNguoiThan
            ];
            $this->data[] = $dataNguoiThan;
        }
    }
    
    public function toJsonEncoded() {
        return json_encode($this->data);
    }
}