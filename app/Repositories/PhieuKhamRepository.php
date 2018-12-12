<?php
namespace App\Repositories;

use App\Models\PhieuYLenh;
use App\Models\YLenh;
use App\Repositories\BaseRepositoryV2;
use DB;

class PhieuKhamRepository extends BaseRepositoryV2
{
    private $yLenhModel;
    public function __construct(){
        parent::__construct();
        $this->yLenhModel = app()->make(YLenh::class);
    }
    public function getModel() {
        return PhieuYLenh::class;
    }

    public function getListPhieuKham($limit = 100, $page = 1) {
        
        //loai phieu y lenh la phieu kham
        $loai_phieu_y_lenh = 2;
        
        $offset = ($page - 1) * $limit;
        
        $columns = [
            'phieu_y_lenh.id',
            'auth_users.name as nguoi_tao',
            'phong.ten_phong',
            'khoa.ten_khoa',
            'hsba.ngay_tao as ngay_tao',
        ];
        
        $query = $this->model->where('loai_phieu_y_lenh','=',$loai_phieu_y_lenh)
                ->join('auth_users', 'auth_users.id', '=', 'phieu_y_lenh.auth_users_id')
                ->join('hsba', 'hsba.id', '=', 'phieu_y_lenh.hsba_id')
                ->join('phong', 'phong.id', '=', 'phieu_y_lenh.phong_id')
                ->join('khoa', 'khoa.id', '=', 'phieu_y_lenh.khoa_id');
                //->join('stt_phong_kham', 'phieu_y_lenh.hsba_id', '=', 'stt_phong_kham.hsba_id');
                //->leftJoin('auth_users as au', 'au.id', '=', 'stt_phong_kham.auth_users_id');
                //->join('stt_phong_kham', 'stt_phong_kham.hsba_id', '=', 'hsba.id');
                
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('phieu_y_lenh.id', 'asc')
                        ->offset($offset)
                        ->limit($limit)->get($columns);
            
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
    
    public function getListYLenhByPhieuKham($phieuKhamId, $limit = 100, $page = 1) {
        
        $offset = ($page - 1) * $limit;
        
        $columns = [
            'y_lenh.id',
            'danh_muc_dich_vu.ten',
            'y_lenh.so_luong'
        ];
        
        $query = $this->yLenhModel->leftJoin('phieu_y_lenh', 'phieu_y_lenh.id','=','y_lenh.phieu_y_lenh_id')
                        ->leftJoin('danh_muc_dich_vu', 'y_lenh.ma','=','danh_muc_dich_vu.ma')
                        ->where('y_lenh.phieu_y_lenh_id','=',$phieuKhamId);
                
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->offset($offset)
                        ->limit($limit)
                        ->get($columns);
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
    
}