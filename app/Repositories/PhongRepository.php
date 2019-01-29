<?php
namespace App\Repositories;

use DB;
use App\Models\Phong;
use App\Repositories\BaseRepositoryV2;

class PhongRepository extends BaseRepositoryV2
{
    const BENH_AN_KHAM_BENH = 24;
    const TRANG_THAI_HOAT_DONG = 1;
    const PHONG_HANH_CHINH = 1;
    
    public function getModel()
    {
        return Phong::class;
    }
    
    public function getListPhong($loaiPhong,$khoaId)
    {
        $phong = $this->model->where([
                                'loai_phong'=>$loaiPhong,
                                'khoa_id'=>$khoaId,
                                'loai_benh_an'=>self::BENH_AN_KHAM_BENH,
                                'trang_thai'=>self::TRANG_THAI_HOAT_DONG
                            ])
                            ->orderBy('ten_phong')
                            ->get();
        return $phong;    
    }
    
    public function getNhomPhong($loaiPhong,$khoaId)
    {
        $phong = $this->model->where([
                                'loai_phong'=>$loaiPhong,
                                'khoa_id'=>$khoaId,
                                'loai_benh_an'=>self::BENH_AN_KHAM_BENH,
                                'trang_thai'=>self::TRANG_THAI_HOAT_DONG
                            ])
                            ->orderBy('ten_nhom')
                            ->distinct()
                            ->get(['ten_nhom','ma_nhom']);
        return $phong;    
    }
    
    public function getDataById($id)
    {
        $phong = $this->model->where(['id'=>$id])
                            ->get()
                            ->first();
        return $phong;
    }
    
    public function getPhongHanhChinhByKhoaID($khoaId)
    {
        $phong = $this->model->where([
                                ['khoa_id', '=', $khoaId],
                                ['loai_phong', '=', self::PHONG_HANH_CHINH],
                                ['loai_benh_an', '!=', self::BENH_AN_KHAM_BENH]
                            ])
                            ->get()
                            ->first();
        return $phong;
    }
}