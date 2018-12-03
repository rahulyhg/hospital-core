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
        $dataSet = DB::table('danh_muc_trang_thai')
                ->where('khoa','loai_vien_phi')
                ->get();
        return $dataSet;    
    }
    
    public function getListDoiTuongBenhNhan()
    {
        $dataSet = DB::table('danh_muc_trang_thai')
                ->where('khoa','doi_tuong_benh_nhan')
                ->get();
        return $dataSet;    
    }
    
    public function getListKetQuaDieuTri()
    {
        $dataSet = DB::table('danh_muc_trang_thai')
                ->where('khoa','ket_qua_dieu_tri')
                ->get();
        return $dataSet;    
    }
    
    public function getListGiaiPhauBenh()
    {
        $dataSet = DB::table('danh_muc_trang_thai')
                ->where('khoa','giai_phau_benh')
                ->get();
        return $dataSet;    
    }
    
    public function getListXuTri()
    {
        $dataSet = DB::table('danh_muc_trang_thai')
                ->where('khoa','xu_tri')
                ->get();
        return $dataSet;    
    }
    
    public function getListDanhMucDichVu($limit = 100, $page = 1)
    {
        $offset = ($page - 1) * $limit;
        
        $query = $this->model;
            
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->offset($offset)
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
    
    public function getListDanhMucTrangThaiByKhoa($khoa) {
        //$dataSet = $this->model->where('khoa',$khoa)->get();
        $dataSet = DB::table('danh_muc_trang_thai')
                ->where('khoa',$khoa)
                ->get();
        return $dataSet;
        
        
    }
    
    public function createDanhMucTrangThai(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function updateDanhMucTrangThai($dmttId, array $input)
    {
        $dmtt = $this->model->findOrFail($dmttId);
		$dmtt->update($input);
    }
    
    public function deleteDanhMucTrangThai($dmttId)
    {
        $this->model->destroy($dmttId);
    }
    
    public function getDanhMucTrangThaiById($id) {
        $data = $this->model->where('id',$id)->first();
        return $data;    
        
    }
}