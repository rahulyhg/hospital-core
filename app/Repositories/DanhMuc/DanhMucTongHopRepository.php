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
    
    public function getListTinh()
    {
        $tinh = DB::table('hanh_chinh')
                ->where('ma_tinh','<>',0)
                ->get();
        return $tinh;    
    }
    
    public function getListHuyen($maTinh)
    {
        $huyen = DB::table('hanh_chinh')
                ->where('ma_huyen','<>',0)
                ->where('huyen_matinh','=',$maTinh)
                ->get();
        return $huyen;    
    }
    
    public function getListXa($maHuyen,$maTinh)
    {
        $huyen = DB::table('hanh_chinh')
                ->where('ma_xa','<>',0)
                ->where('xa_mahuyen','=',$maHuyen)
                ->where('xa_matinh','=',$maTinh)
                ->get();
        return $huyen;    
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
    
    public function getListDanhMucTongHop($limit = 100, $page = 1) {
        $offset = ($page - 1) * $limit;
        $query = $this->model->where('id', '>', 0);
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
    
    public function getThxByKey($thxKey)
    {
        $key = explode(" ",$thxKey);
        $result = array();
        if(count($key)==1){
            $dataTinh = DB::table('hanh_chinh')
                ->where('kyhieu_tinh','like',strtoupper($key[0]).'%')
                ->get(['ma_tinh','ten_tinh','kyhieu_tinh']);
            if($dataTinh){
                foreach($dataTinh as $itemTinh){
                    $dataHuyen = DB::table('hanh_chinh')
                        ->where('huyen_matinh',$itemTinh->ma_tinh)
                        ->where('ma_huyen','<>',0)
                        ->get(['ma_huyen','ten_huyen','huyen_matinh','kyhieu_huyen']);
                    if($dataHuyen){
                        foreach($dataHuyen as $itemHuyen){
                            $dataXa = DB::table('hanh_chinh')
                                ->where('xa_mahuyen',$itemHuyen->ma_huyen)
                                ->where('xa_matinh',$itemTinh->ma_tinh)
                                ->get(['ten_xa','ma_xa','kyhieu_xa']);
                            if($dataXa){
                                foreach($dataXa as $itemXa){
                                    $result[] = [
                                        'dia_chi'=>$itemTinh->ten_tinh.' - '.$itemHuyen->ten_huyen.' - '.$itemXa->ten_xa,
                                        'ma_tinh'=>$itemTinh->ma_tinh,
                                        'ma_huyen'=>$itemHuyen->ma_huyen,
                                        'ma_xa'=>$itemXa->ma_xa,
                                        'thx_code'=>$itemTinh->ma_tinh.' '.$itemHuyen->ma_huyen.' '.$itemXa->ma_xa,
                                        'thx_ky_hieu'=>$itemTinh->kyhieu_tinh.' '.$itemHuyen->kyhieu_huyen.' '.$itemXa->kyhieu_xa
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
        if(count($key)==2){
            $dataTinh = DB::table('hanh_chinh')
                    ->where('kyhieu_tinh','like',strtoupper($key[0]).'%')
                    ->get(['ma_tinh','ten_tinh','kyhieu_tinh']);
            if($dataTinh){
                foreach($dataTinh as $itemTinh){
                    $dataHuyen = DB::table('hanh_chinh')
                        ->where('huyen_matinh',$itemTinh->ma_tinh)
                        ->where('ma_huyen','<>',0)
                        ->where('kyhieu_huyen','like',strtoupper($key[1]).'%')
                        ->get(['ma_huyen','ten_huyen','huyen_matinh','kyhieu_huyen']);
                    if($dataHuyen){
                        foreach($dataHuyen as $itemHuyen){
                            $dataXa = DB::table('hanh_chinh')
                                ->where('xa_mahuyen',$itemHuyen->ma_huyen)
                                ->where('xa_matinh',$itemTinh->ma_tinh)
                                ->where('ma_xa','<>',0)
                                ->get(['ten_xa','kyhieu_xa','ma_xa']);
                            if($dataXa){
                                foreach($dataXa as $itemXa){
                                    $result[] = [
                                        'dia_chi'=>$itemTinh->ten_tinh.' - '.$itemHuyen->ten_huyen.' - '.$itemXa->ten_xa,
                                        'ma_tinh'=>$itemTinh->ma_tinh,
                                        'ma_huyen'=>$itemHuyen->ma_huyen,
                                        'ma_xa'=>$itemXa->ma_xa,
                                        'thx_code'=>$itemTinh->ma_tinh.' '.$itemHuyen->ma_huyen.' '.$itemXa->ma_xa,
                                        'thx_ky_hieu'=>$itemTinh->kyhieu_tinh.' '.$itemHuyen->kyhieu_huyen.' '.$itemXa->kyhieu_xa
                                    ];                                    
                                }
                            }
                        }
                    }
                }
            }
        }
        if(count($key)==3){
            $dataTinh = DB::table('hanh_chinh')
                    ->where('kyhieu_tinh','like',strtoupper($key[0]).'%')
                    ->get(['ma_tinh','ten_tinh','kyhieu_tinh']);
            if($dataTinh){
                foreach($dataTinh as $itemTinh){
                    $dataHuyen = DB::table('hanh_chinh')
                        ->where('huyen_matinh',$itemTinh->ma_tinh)
                        ->where('ma_huyen','<>',0)
                        ->where('kyhieu_huyen','like',strtoupper($key[1]).'%')
                        ->get(['ma_huyen','ten_huyen','huyen_matinh','kyhieu_huyen']);
                    if($dataHuyen){
                        foreach($dataHuyen as $itemHuyen){
                            $dataXa = DB::table('hanh_chinh')
                                ->where('kyhieu_xa','like',strtoupper($key[2]).'%')
                                ->where('xa_mahuyen',$itemHuyen->ma_huyen)
                                ->where('xa_matinh',$itemTinh->ma_tinh)
                                ->where('ma_xa','<>',0)
                                ->get(['ten_xa','kyhieu_xa','ma_xa']);
                            if($dataXa){
                                foreach($dataXa as $itemXa){
                                    $result[] = [
                                        'dia_chi'=>$itemTinh->ten_tinh.' - '.$itemHuyen->ten_huyen.' - '.$itemXa->ten_xa,
                                        'ma_tinh'=>$itemTinh->ma_tinh,
                                        'ma_huyen'=>$itemHuyen->ma_huyen,
                                        'ma_xa'=>$itemXa->ma_xa,
                                        'thx_code'=>$itemTinh->ma_tinh.' '.$itemHuyen->ma_huyen.' '.$itemXa->ma_xa,
                                        'thx_ky_hieu'=>$itemTinh->kyhieu_tinh.' '.$itemHuyen->kyhieu_huyen.' '.$itemXa->kyhieu_xa
                                    ];                                    
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }    
}