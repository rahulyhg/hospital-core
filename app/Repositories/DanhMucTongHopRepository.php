<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;

class DanhMucTongHopRepository extends BaseRepository
{

    public function getListNgheNghiep()
    {
        $ngheNghiep = DB::table('danh_muc_tong_hop')
                ->where('khoa','nghe_nghiep')
                ->get();
        return $ngheNghiep;    
    }
    
    public function getListBenhVien()
    {
        $benhVien = DB::table('benhvien')
                ->orderBy('benhvienid')
                ->get();
        return $benhVien;    
    }
    
    public function getListDanToc()
    {
        $danToc = DB::table('danh_muc_tong_hop')
                ->where('khoa','dan_toc')
                ->get();
        return $danToc;    
    }
    
    public function getListQuocTich()
    {
        $quocTich = DB::table('danh_muc_tong_hop')
                ->where('khoa','quoc_tich')
                ->get();
        return $quocTich;    
    }
    
    public function getListTinh()
    {
        $tinh = DB::table('hanh_chinh')
                ->where('ma_tinh','<>',0)
                ->get();
        return $tinh;    
    }
    
    public function getTenDanhMucTongHopByKhoaGiaTri($khoa, $gia_tri)
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
    
    public function getDataTinh($value)
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
    public function getDataHuyen($huyen_matinh, $ma_huyen)
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
    public function getDataXa($xa_matinh, $xa_mahuyen, $ma_xa)
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
    public function getListHuyen($maTinh)
    {
        $huyen = DB::table('hanh_chinh')
                ->where('ma_tinh',0)
                ->where('huyen_matinh',$maTinh)
                ->orderBy('ten_huyen')
                ->get();
        return $huyen;    
    }
    
    public function getListXa($maHuyen,$maTinh)
    {
        $xa = DB::table('hanh_chinh')
                ->where([
                    'ma_tinh' => '0',
                    'ma_huyen' => '0',
                    'xa_mahuyen'=>$maHuyen,
                    'xa_matinh'=>$maTinh])
                ->get();
        return $xa;    
    }
}