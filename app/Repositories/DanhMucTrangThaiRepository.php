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
    
    public function getDanhMucTrangThaiByKhoa($khoa) {
        $dataset = DB::table('danh_muc_trang_thai')
                ->where('khoa',$khoa)
                ->get();
        return $dataset;    
        
    }
    
    public function createDanhMucTrangThai(array $input)
    {
        //$id = DanhMucTrangThai::create($input)->id;
        $id = DB::table('danh_muc_trang_thai')->insertGetId($input);
        //$id = DB::getPdo()->lastInsertId();
        return $id;
    }
    
    public function updateDanhMucTrangThai($dmttId, array $input)
    {
        //$dmtt = DanhMucTrangThai::findOrFail($dmttId);
		//$dmtt->update($input);
		DB::table('danh_muc_trang_thai')
            ->where('id', $dmttId)
            ->update($input);
    }
    
    public function deleteDanhMucTrangThai($dmttId)
    {
        //DanhMucTrangThai::destroy($dmttId);
        DB::table('danh_muc_trang_thai')->where('id', $dmttId)->delete();
        
    }
    
    
}