<?php
namespace App\Repositories\DanhMuc;
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
    
    public function getListKetQuaDieuTri()
    {
        $dataSet = DB::table('danh_muc_trang_thai')
                ->where('khoa','ket_qua_dieu_tri')
                ->get();
        return $dataSet;    
    }
    
    public function getListGiaiPhauBenh()
    {
        $dataSet = DB::table('danh_muc_trang_thai')
                ->where('khoa','giai_phau_benh')
                ->get();
        return $dataSet;    
    }
    
    public function getListXuTri()
    {
        $dataSet = DB::table('danh_muc_trang_thai')
                ->where('khoa','xu_tri')
                ->get();
        return $dataSet;    
    }
    
    public function getListDanhMucTrangThaiByKhoa($khoa) {
        $data = DB::table('danh_muc_trang_thai')
                ->where('khoa',$khoa)
                ->get();
        $result = [
            'data'          => $data,
            'page'          => 1,
            'totalPage'     => 1,
            'totalRecord'   => 1
        ];
        //return $data;
        
    }
}