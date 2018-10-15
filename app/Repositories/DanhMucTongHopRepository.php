<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;

class DanhMucTongHopRepository extends BaseRepository
{

    public function getListNgheNghiep()
    {
        $nghenghiep = DB::table('danh_muc_tong_hop')
                ->where('khoa','nghe_nghiep')
                ->get();
        return $nghenghiep;    
    }
    
    public function getListBenhVien()
    {
        $benhvien = DB::table('benhvien')
                ->orderBy('benhvienid')
                ->get();
        return $benhvien;    
    }
    
    public function getListDanToc()
    {
        $dantoc = DB::table('danh_muc_tong_hop')
                ->where('khoa','dan_toc')
                ->get();
        return $dantoc;    
    }
    
    public function getListQuocTich()
    {
        $quoctich = DB::table('danh_muc_tong_hop')
                ->where('khoa','quoc_tich')
                ->get();
        return $quoctich;    
    }
    
    public function getListTinh()
    {
        $tinh = DB::table('hanh_chinh')
                ->where('ma_tinh','<>',0)
                ->get();
        return $tinh;    
    }
    
    public function getListHuyen($matinh)
    {
        $huyen = DB::table('hanh_chinh')
                ->where('ma_tinh',0)
                ->where('huyen_matinh',$matinh)
                ->orderBy('ten_huyen')
                ->get();
        return $huyen;    
    }
    
    public function getListXa($mahuyen,$matinh)
    {
        $xa = DB::table('hanh_chinh')
                ->where([
                    'ma_tinh' => '0',
                    'ma_huyen' => '0',
                    'xa_mahuyen'=>$mahuyen,
                    'xa_matinh'=>$matinh])
                ->get();
        return $xa;    
    }
}