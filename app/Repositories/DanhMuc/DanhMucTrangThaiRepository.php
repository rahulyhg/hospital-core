<?php
namespace App\Repositories\DanhMuc;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\DanhMucTrangThai;

class DanhMucTrangThaiRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return DanhMucTrangThai::class;
    }    

    public function getListLoaiVienPhi()
    {
        $dataSet = $this->model
                ->where('khoa','loai_vien_phi')
                ->get();
        return $dataSet;    
    }
    
    public function getListDoiTuongBenhNhan()
    {
        $dataSet = $this->model
                ->where('khoa','doi_tuong_benh_nhan')
                ->get();
        return $dataSet;    
    }
    
    public function getListKetQuaDieuTri()
    {
        $dataSet = $this->model
                ->where('khoa','ket_qua_dieu_tri')
                ->get();
        return $dataSet;    
    }
    
    public function getListGiaiPhauBenh()
    {
        $dataSet = $this->model
                ->where('khoa','giai_phau_benh')
                ->get();
        return $dataSet;    
    }
    
    public function getListXuTri()
    {
        $dataSet = $this->model
                ->where('khoa','xu_tri')
                ->get();
        return $dataSet;    
    }

    public function getListDanhMucTrangThai($limit = 100, $page = 1, $dienGiai = '', $khoa = '') {
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
    
    public function getDanhMucTrangThaiTheoKhoa($khoa, $limit = 100, $page = 1) {
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
    
    public function createDanhMucTrangThai(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function updateDanhMucTrangThai($dmttId, array $input)
    {
        $dmth = $this->model->findOrFail($dmttId);
		$dmth->update($input);
    }
    
    public function deleteDanhMucTrangThai($dmttId)
    {
        $this->model->destroy($dmttId);
    }
    
    public function getDanhMucTrangThaiById($id) {
        $result = $this->model->find($id); 
        return $result; 
    }
    
    public function getAllKhoa()
    {
        $result = $this->model->select('khoa')->distinct()->get();
        return $result;
    }
    
    public function getListHinhThucChuyen()
    {
        $dataSet = $this->model
                ->where('khoa', 'hinh_thuc_chuyen')
                ->get();
        return $dataSet;    
    }
    
    public function getListTuyen()
    {
        $dataSet = $this->model
                ->where('khoa','tuyen')
                ->get();
        return $dataSet;    
    }
    
    public function getListLyDoChuyen()
    {
        $dataSet = $this->model
                ->where('khoa','ly_do_chuyen')
                ->get();
        return $dataSet;    
    }

}