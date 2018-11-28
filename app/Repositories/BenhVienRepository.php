<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;

class BenhVienRepository extends BaseRepository
{
    public function listBenhVien()
    {
        $dataSet = DB::table('benh_vien')
                ->orderBy('id')
                ->get();
        return $dataSet;    
    }
    
    public function getBenhVienThietLap($benhVienId) {
        $data = [];
        $hospital = DB::table('benh_vien')->find($benhVienId);
        $settingHospital = json_decode($hospital->thiet_lap);
        $khoaKhamBenh = $settingHospital->khoa->khoa_kham_benh;
        $data['khoaHienTai'] = intval($khoaKhamBenh->id); //khoa kham benh
        $data['phongDonTiepID'] = intval($khoaKhamBenh->phong->phong_don_tiep);
        return $data;
    }
}