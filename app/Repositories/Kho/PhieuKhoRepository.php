<?php
namespace App\Repositories\Kho;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\PhieuKho;
use Carbon\Carbon;

class PhieuKhoRepository extends BaseRepositoryV2
{
    const DA_XUAT_STATUS=31;
    
    public function getModel()
    {
        return PhieuKho::class;
    }  
    
    public function createPhieuKho(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function getMaPhieu()
    {
        $where = [
            ['ma_phieu','like','BILL'.'%']
            ];
        $data = $this->model
            ->where($where)
            ->orderBy('id','DESC')
            ->first();
        if($data) {
            $maPhieu = intval(substr($data['ma_phieu'],4))+1;

        }
        else {
            $maPhieu=1;
        }
        return 'BILL'.$maPhieu;   
    } 
    
    public function updateTrangThaiPhieuKho($phieuKhoId,$trangThai)
    {
        $this->model->where('id',$phieuKhoId)->update(['trang_thai'=>$trangThai]);
    }    
    
    public function getPhieuKhoById($id)
    {
        $data = $this->model->findOrFail($id);
        return $data;
    }
    
    public function getThongTinPhieuKhoById($id)
    {
        $column = [
            'phieu_kho.*',
            'kho.ten_kho',
            'auth_users.fullname',
            'nha_cung_cap.ten_nha_cung_cap'
            ];
        $data = $this->model
                    ->where('phieu_kho.id',$id)
                    ->leftJoin('kho','kho.id','=','phieu_kho.kho_id')
                    ->leftJoin('auth_users','auth_users.id','=','phieu_kho.nhan_vien_yeu_cau')
                    ->leftJoin('nha_cung_cap','nha_cung_cap.id','=','phieu_kho.ncc_id')
                    ->get($column)
                    ->first();
        return $data;
    }   
    
    public function getListPhieuKhoByKhoIdXuLy($startDay,$endDay,$khoIdXuLy)
    {
        if($startDay == $endDay){
            $model = $this->model->where('thoi_gian_yeu_cau', '=',$startDay);
        } else {
            $model = $this->model->whereBetween('thoi_gian_yeu_cau', [Carbon::parse($startDay), Carbon::parse($endDay)]);
        }
        
        $column=[
            'phieu_kho.*',
            'kho.ten_kho'
            ];
        
        $data = $model
            ->where(function ($model) use($khoIdXuLy) {
                $model->where('kho_id_xu_ly','=',$khoIdXuLy)->orWhere('kho_id','=',$khoIdXuLy);
            })
            ->leftJoin('kho','kho.id','=','phieu_kho.kho_id')
            ->orderBy('phieu_kho.id','DESC')
            ->get($column);
            
        $result=[];
        $phieuYeuCau=[];
        $phieuDaTao=[];
        
        foreach($data as $item) {
            if($item->kho_id==$khoIdXuLy && $item->kho_id_xu_ly==$khoIdXuLy) {
                $phieuDaTao[]=$item;
            }
            else if($item->kho_id==$khoIdXuLy && $item->kho_id!=$item->kho_id_xu_ly) {
                $phieuDaTao[]=$item;
            }
            else if($item->kho_id_xu_ly==$khoIdXuLy && $item->kho_id!=$item->kho_id_xu_ly) {
                $phieuYeuCau[]=$item;
            }
        }
        $result=[
            'list_phieu_yeu_cau' => $phieuYeuCau,
            'list_phieu_da_tao' => $phieuDaTao,
            ];
        return $result;
    }
    
    public function updateAndGetPhieuKho($phieuKhoId,$nhanVienDuyetId)
    {
        $this->model
            ->where('id',$phieuKhoId)
            ->update([
                'trang_thai' => self::DA_XUAT_STATUS,
                'nhan_vien_duyet' => $nhanVienDuyetId,
                'thoi_gian_duyet' => Carbon::now(),
                'phieu_kho_yeu_cau_id' => $phieuKhoId
            ]);
        return $this->model->findOrFail($phieuKhoId);
    }    
}