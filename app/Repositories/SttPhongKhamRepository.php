<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\SttPhongKham;
use App\Models\Hsba;
use App\Models\HsbaKhoaPhong;
use Carbon\Carbon;

class SttPhongKhamRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return SttPhongKham::class;
    }
    
    public function countSttPhongKham($loaiStt, $maNhom, $khoaId)
    {
        $today = Carbon::today();
        
        $where = [
            ['phong.ma_nhom', '=', $maNhom],
            ['phong.khoa_id', '=', $khoaId],
            ['phong.trang_thai', '=', 1]
        ];
        
        $data = DB::table('phong')
                    ->select('phong.id', 'phong.ten_phong', 'phong.so_phong', 'phong.ma_nhom', DB::raw('count(sttpk.id) as total'))
                    ->leftJoin('stt_phong_kham as sttpk', function($join) use ($today) {
                        $join->on('sttpk.phong_id', '=', 'phong.id')
                            ->whereBetween('thoi_gian_phat', [Carbon::parse($today)->startOfDay(), Carbon::parse($today)->endOfDay()]);
                    })
                    ->where($where)
                    ->groupBy('phong.id')
                    ->orderBy('total', 'asc')
                    ->orderBy('phong.id', 'asc')
                    ->get()
                    ->first();
                    
        return $data;
    }
    
    public function createSttPhongKham(array $params)
    {
        $today = Carbon::today();
        
        $where = [
            ['loai_stt', '=', $params['loai_stt']],
            ['phong_id', '=', $params['phong_id']],
            ['khoa_id', '=', $params['khoa_id']],
            ['benh_vien_id', '=', $params['benh_vien_id']]
        ];
        
        $result = $this->model->where($where)
                            ->whereBetween('thoi_gian_phat', [Carbon::parse($today)->startOfDay(), Carbon::parse($today)->endOfDay()])
                            ->orderBy('id', 'desc')
                            ->first();
                            
        $sttCurrent = $result['so_thu_tu'];
        
        if($sttCurrent == ''){
            $soThuTu = 1;
        } else {
            $soThuTu = $sttCurrent + 1;
        }
        
        $attributes = ['loai_stt' => $params['loai_stt'],
                        'so_thu_tu' => $soThuTu,
                        'trang_thai' => 1,
                        'thoi_gian_phat' => Carbon::now()->toDateTimeString(),
                        'thoi_gian_goi' => null,
                        'thoi_gian_ket_thuc' => null,
                        'ten_benh_nhan' => $params['ten_benh_nhan'],
                        'phong_id' => $params['phong_id'],
                        'khoa_id' => $params['khoa_id'],
                        'benh_vien_id' => $params['benh_vien_id'],
                        'hsba_id' => $params['hsba_id'],
                        'hsba_khoa_phong_id' => $params['hsba_khoa_phong_id'],
                        'auth_users_id' => null,
                        'stt_don_tiep_id' => $params['stt_don_tiep_id'],
                        'ten_phong' => $params['ten_phong'],
                        'ma_phong' => $params['ma_phong']
                    ];
                    
        $this->model->create($attributes);
        
        $stt = $soThuTu;
        
        return $stt;
    }
    
    public function getListPhongKham($hsbaId)
    {
        $column = [
            'hsba_id',
            'hsba_khoa_phong_id',
            'phong_id',
            'ten_phong',
            'ma_phong'
        ];
        
        $data = $this->model->where('hsba_id', '=', $hsbaId)
                            ->get($column);
                            
        return $data;
    }
    
    public function goiSttPhongKham(array $input)
    {
        $loaiStt = $input['loaiStt'];
        $phongId = $input['phongId'];
        $benhVienId = $input['benhVienId'];
        $authUsersId = $input['authUsersId'];
        $today = Carbon::today();
        
        $where = [
            ['loai_stt', '=', $loaiStt],
            ['trang_thai', '=', 1],
            ['phong_id', '=', $phongId],
            ['benh_vien_id', '=', $benhVienId]
        ];
        
        $result = $this->model->where($where)
                            ->whereBetween('thoi_gian_phat', [Carbon::parse($today)->startOfDay(), Carbon::parse($today)->endOfDay()])
                            ->orderBy('id', 'asc')
                            ->first();
                            
        if($result) {
            $id = $result['id'];
                            
            $attributes = ['trang_thai' => 2,
                            'thoi_gian_goi' => Carbon::now()->toDateTimeString(),
                            'auth_users_id' => $authUsersId
                        ];
            
            $this->model->where('id', '=', $id)->update($attributes);
            
            $data = $this->model->findOrFail($id);
        } else {
            $data = null;
        }
        
        return $data;
    }
    
    public function loadSttPhongKham(array $input)
    {
        $phongId = $input['phongId'];
        $benhVienId = $input['benhVienId'];
        $today = Carbon::today();
        
        $where = [
            ['trang_thai', '>=', 2],
            ['phong_id', '=', $phongId],
            ['benh_vien_id', '=', $benhVienId]
        ];
        
        $result = $this->model->where($where)
                            ->whereBetween('thoi_gian_goi', [Carbon::parse($today)->startOfDay(), Carbon::parse($today)->endOfDay()])
                            ->orderBy('thoi_gian_goi', 'desc')
                            ->skip(0)
                            ->take(5)
                            ->get();
                            
        return $result;
    }
    
    public function finishSttPhongKham($sttId)
    {
        $today = Carbon::today();
        
        $attributes = ['trang_thai' => 3,
                        'thoi_gian_ket_thuc' => Carbon::now()->toDateTimeString()
                    ];
                    
        $this->model->where('id', '=', $sttId)->update($attributes);
    }
    
}