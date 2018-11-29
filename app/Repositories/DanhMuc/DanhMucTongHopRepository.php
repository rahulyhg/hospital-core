<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\DanhMucTongHop;

class DanhMucTongHopRepository extends BaseRepositoryV2
{

    public function getModel()
    {
        return DanhMucTongHop::class;
    }

    public function getListNgheNghiep()
    {
        $ngheNghiep = DB::table('danh_muc_tong_hop')
                ->where('khoa','nghe_nghiep')
                ->get();
        return $ngheNghiep;    
    }
    
    public function getListBenhVien()
    {
        $benhVien = DB::table('danh_muc_benh_vien')
                ->orderBy('id')
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
                ->orderBy('gia_tri')
                ->get();
        return $quocTich;    
    }
    
    public function getListTinh()
    {
        $tinh = DB::table('danh_muc_tong_hop')
                ->where('khoa','tinh')
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
        $data = DB::table('hanh_chinh')
                ->whereRaw("upper(hanh_chinh.ten_tinh) like '%$value%'")
                ->get();
        $array = json_decode($data, true);
      
        return collect($array)->first();  
    }
  
    public function getDataHuyen($huyen_matinh, $ten_huyen)
    {
        $data = DB::table('hanh_chinh')
                ->where('hanh_chinh.huyen_matinh', '=', $huyen_matinh)
                ->whereRaw("upper(hanh_chinh.ten_huyen) like '%$ten_huyen%'")
                ->get();
        $array = json_decode($data, true);
      
        return collect($array)->first(); 
    }
    
    public function getDataXa($xa_matinh, $xa_mahuyen, $ten_xa)
    {
        $where = [
                ['hanh_chinh.xa_matinh', '=', $xa_matinh],
                ['hanh_chinh.xa_mahuyen', '=', $xa_mahuyen],
            ];
        $data = DB::table('hanh_chinh')
                ->where($where)
                ->whereRaw("upper(hanh_chinh.ten_xa) like '%$ten_xa%'")
                ->get();
        $array = json_decode($data, true);
      
        return collect($array)->first();  
    }
    
    public function getIdTinhByTen($tenTinh)
    {
        
    }
    
    public function getDanhMucTongHopTheoKhoa($khoa, $limit = 100, $page = 1) {
        $offset = ($page - 1) * $limit;
        
        $data = $this->model
                ->where('khoa', $khoa)
                ->orderBy('id', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get();
        return $data;
    }
    
    public function createDanhMucTongHop(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function updateDanhMucTongHop($dmthId, array $input)
    {
        $dmth = $this->model->findOrFail($dmthId);
		$dmth->update($input);
    }
    
    public function deleteDanhMucTongHop($dmthId)
    {
        $this->model->destroy($dmthId);
    }
}
