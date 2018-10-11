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
    
    public function getTen_DanhMucTongHopByKhoa_GiaTri($khoa, $gia_tri)
    {
        $where = [
                ['danh_muc_tong_hop.khoa', '=', $khoa],
                ['danh_muc_tong_hop.gia_tri', '=', $gia_tri]
            ];
        $column = [
            'danh_muc_tong_hop.gia_tri',
            'danh_muc_tong_hop.dien_giai'
        ];
        $data = DB::table('danh_muc_tong_hop')
                ->where($where)
                ->get($column);
        $array = json_decode($data, true);
        return collect($array)->first();  
    }
    
    public function getData_Tinh($value)
    {
        $where = [
                ['hanh_chinh.ma_tinh', '=', $value]
            ];
        $data = DB::table('hanh_chinh')
                ->where($where)
                ->get();
        $array = json_decode($data, true);
        return collect($array)->first(); 
    }
    public function getData_Huyen($huyen_matinh, $ma_huyen)
    {
        $where = [
                ['hanh_chinh.huyen_matinh', '=', $huyen_matinh],
                ['hanh_chinh.ma_huyen', '=', $ma_huyen]
            ];
        $data = DB::table('hanh_chinh')
                ->where($where)
                ->get();
        $array = json_decode($data, true);
        return collect($array)->first(); 
    }
    public function getData_Xa($xa_matinh, $xa_mahuyen, $ma_xa)
    {
        $where = [
                ['hanh_chinh.xa_matinh', '=', $xa_matinh],
                ['hanh_chinh.xa_mahuyen', '=', $xa_mahuyen],
                ['hanh_chinh.ma_xa', '=', $ma_xa]
            ];
        $data = DB::table('hanh_chinh')
                ->where($where)
                ->get();
        $array = json_decode($data, true);
        return collect($array)->first();  
    }
}