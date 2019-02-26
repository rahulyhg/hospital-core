<?php
namespace App\Repositories;
use DB;
use App\Models\BenhVien;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Config;
use Exception;

class BenhVienRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return BenhVien::class;
    }
    
    public function listBenhVien()
    {
        $dataSet = $this->model
                ->orderBy('id')
                ->get();
        return $dataSet;    
    }
    
    public function getById($id)
    {
        $data = $this->model->findOrFail($id);
        return $data;    
    }    
    
    public function getBenhVienThietLap($benhVienId) {
        $data = [];
        $hospital = $this->model->find($benhVienId);
        $settingHospital = json_decode($hospital->thiet_lap);
        if(empty($hospital->thiet_lap)) {
            throw new Exception(Config::get('constants.error_get_thiet_lap_benh_vien'));
        }
        
        $khoaKhamBenh = $settingHospital->khoa->khoa_kham_benh;
        $data['bucket']     = $settingHospital->bucket;
        $data['khoaHienTai'] = intval($khoaKhamBenh->id); //khoa kham benh
        $data['khoaKhamBenh'] = intval($khoaKhamBenh->id); //khoa kham benh
        $data['phongDonTiepID'] = intval($khoaKhamBenh->phong->phong_don_tiep);
        return $data;
    }
}