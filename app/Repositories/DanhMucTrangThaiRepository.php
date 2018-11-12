<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;

class DanhMucTrangThaiRepository extends BaseRepository
{

    public function getListLoaiVienPhi()
    {
        $dataSet = DB::table('danh_muc_trang_thai')
                ->where('khoa','loai_vien_phi')
                ->get();
        return $dataSet;    
    }
    
    public function getListDoiTuongBenhNhan()
    {
        $dataSet = DB::table('danh_muc_trang_thai')
                ->where('khoa','doi_tuong_benh_nhan')
                ->get();
        return $dataSet;    
    }
}