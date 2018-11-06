<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\SttPhongKham;
use Carbon\Carbon;

class SttPhongKhamRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return SttPhongKham::class;
    }
    
    public function countSttPhongKham($loaiStt, $maNhom, $khoaId)
    {
        $where = [
            ['phong.ma_nhom', '=', $maNhom],
            ['phong.khoa_id', '=', $khoaId],
            ['phong.trang_thai', '=', 1]
        ];
        
        $data = DB::table('phong')
                    ->select('phong.id', 'phong.ten_phong', 'phong.so_phong', DB::raw('count(sttpk.id) as total'))
                    ->leftJoin('stt_phong_kham as sttpk', function($join) use ($loaiStt) {
                        $join->on('sttpk.phong_id', '=', 'phong.id')
                            ->where('sttpk.loai_stt', '=', $loaiStt);
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
                        'ten_phong' => $params['ten_phong']
                    ];
                    
        $this->model->create($attributes);
        
        $stt = $soThuTu;
        
        return $stt;
    }
    
}