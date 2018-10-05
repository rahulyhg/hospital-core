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
        $tinh = DB::table('danh_muc_tong_hop')
                ->where('khoa','tinh')
                ->get();
        return $tinh;    
    }
}