<?php
namespace App\Repositories\DanhMuc;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\DanhMucTrangThai;


class DanhMucTrangThaiRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return DanhMucTrangThai::class;
    }

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
        //$dataSet = $this->model->where('khoa',$khoa)->get();
        $dataSet = DB::table('danh_muc_trang_thai')
                ->where('khoa',$khoa)
                ->get();
        return [
            'data' => $dataSet,
            'date' => ''
        ];
        
        
    }
}