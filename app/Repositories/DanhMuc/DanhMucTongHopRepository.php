<?php
namespace App\Repositories\DanhMuc;
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
        $ngheNghiep = $this->model
                ->where('khoa','nghe_nghiep')
                ->get();
        return $ngheNghiep;    
    }
    
    public function getListBenhVien()
    {
        $benhVien = $this->model
                ->orderBy('id')
                ->get();
        return $benhVien;    
    }
    
    public function getListDanToc()
    {
        $danToc = $this->model
                ->where('khoa','dan_toc')
                ->get();
        return $danToc;    
    }
    
    public function getListQuocTich()
    {
        $quocTich = $this->model
                ->where('khoa','quoc_tich')
                ->orderBy('gia_tri')
                ->get();
        return $quocTich;    
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
        $data = $this->model
                ->where($where)
                ->get($column);
        $array = json_decode($data, true);
      
        return collect($array)->first();  
    }
    
    public function getIdTinhByTen($tenTinh)
    {
        
    }
    
    public function getListDanhMucTongHop($limit = 100, $page = 1, $dienGiai = '', $khoa = '') {
        $offset = ($page - 1) * $limit;
        
        $query = $this->model
                ->where('dien_giai', 'like', '%' . $dienGiai . '%');
                
        if($khoa != "") {
            $query->where('khoa', $khoa);
        }        
        
        $totalRecord = $query->count();
        
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('id', 'desc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
        } else {
            $totalPage = 0;
            $data = [];
            $page = 0;
            $totalRecord = 0;
        }
        
        $result = [
            'data'          => $data,
            'page'          => $page,
            'totalPage'     => $totalPage,
            'totalRecord'   => $totalRecord
        ];
        
        return $result;
    }
    
    public function getDataDanhMucTongHopById($dmthId)
    {
        $result = $this->model->find($dmthId); 
        return $result;
    }
    
    public function getDanhMucTongHopTheoKhoa($khoa, $limit = 100, $page = 1) {
        $offset = ($page - 1) * $limit;
        $query = $this->model->where('khoa', $khoa);
        $totalRecord = $query->count();
        
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('id', 'desc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
        } else {
            $totalPage = 0;
            $data = [];
            $page = 0;
            $totalRecord = 0;
        }
        
        $result = [
            'data'          => $data,
            'page'          => $page,
            'totalPage'     => $totalPage,
            'totalRecord'   => $totalRecord
        ];
        
        return $result;
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
    
    public function getAllKhoa()
    {
        $data = $this->model->select('khoa')->distinct()->get();
    
        return $data;
    }

}