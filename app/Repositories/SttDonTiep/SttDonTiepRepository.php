<?php

namespace App\Repositories\SttDonTiep;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\SttDonTiep;
use Carbon\Carbon;

class SttDonTiepRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return SttDonTiep::class;
    }
    
    public function getSttDonTiep($loai_stt, $ma_so_kiosk, $phong_id, $benh_vien_id)
    {
        $today = Carbon::today();
        
        $where = [
            ['loai_stt', '=', $loai_stt],
            ['phong_id', '=', $phong_id],
            ['benh_vien_id', '=', $benh_vien_id]
        ];
        
        $result = $this->model->where($where)
                            ->whereBetween('thoi_gian_phat', [Carbon::parse($today)->startOfDay(), Carbon::parse($today)->endOfDay()])
                            ->orderBy('id', 'desc')
                            ->first();
                            
        $stt_current = $result['so_thu_tu'];
        
        if($stt_current == ''){
            $so_thu_tu = 1;
        } else {
            $so_thu_tu = $stt_current + 1;
        }
        
        $attributes = ['loai_stt' => $loai_stt,
                        'so_thu_tu' => $so_thu_tu,
                        'trang_thai' => 1,
                        'thoi_gian_phat' => Carbon::now()->toDateTimeString(),
                        'thoi_gian_goi' => null,
                        'thoi_gian_ket_thuc' => null,
                        'ma_so_kiosk' => $ma_so_kiosk,
                        'phong_id' => $phong_id,
                        'benh_vien_id' => $benh_vien_id,
                        'thong_tin_so_bo' => null,
                        'quay_so' => null,
                    ];
                    
        $this->model->create($attributes);
        
        $stt = $loai_stt . sprintf('%03d', $so_thu_tu);
        
        return $stt;
    }
    
    public function goiSttDonTiep($request)
    {
        $loai_stt = $request->query('loai_stt', 'C');
        $phong_id = $request->query('phong_id', 1);
        $benh_vien_id = $request->query('benh_vien_id', 1);
        $quay_so = $request->query('quay_so', 1);
        $today = Carbon::today();
        
        $where = [
            ['loai_stt', '=', $loai_stt],
            ['trang_thai', '=', 1],
            ['phong_id', '=', $phong_id],
            ['benh_vien_id', '=', $benh_vien_id]
        ];
        
        $result = $this->model->where($where)
                            ->whereBetween('thoi_gian_phat', [Carbon::parse($today)->startOfDay(), Carbon::parse($today)->endOfDay()])
                            ->orderBy('id', 'asc')
                            ->first();
        
        $id = $result['id'];
                            
        $attributes = ['trang_thai' => 2,
                        'thoi_gian_goi' => Carbon::now()->toDateTimeString(),
                        'quay_so' => $quay_so,
                    ];
        
        $this->model->where('id', '=', $id)->update($attributes);
        
        return $result;
    }

    public function loadSttDonTiep($request)
    {
        $phong_id = $request->query('phong_id', 1);
        $benh_vien_id = $request->query('benh_vien_id', 1);
        $today = Carbon::today();
        
        $where = [
            ['trang_thai', '=', 2],
            ['phong_id', '=', $phong_id],
            ['benh_vien_id', '=', $benh_vien_id]
        ];
        
        $result = $this->model->where($where)
                            ->whereBetween('thoi_gian_goi', [Carbon::parse($today)->startOfDay(), Carbon::parse($today)->endOfDay()])
                            ->orderBy('thoi_gian_goi', 'desc')
                            ->skip(0)
                            ->take(5)
                            ->get();
                            
        return $result;
    }
    
    public function getInfoPatientByStt($stt, $phong_id, $benh_vien_id)
    {
        $today = Carbon::today();
        
        $dieu_kien = [
            'loai_stt'      => $stt[0],
            'so_thu_tu'     => (int)substr($stt, 1, 4),
            'phong_id'      => $phong_id,
            'benh_vien_id'  => $benh_vien_id
        ];
        
        $data = DB::table('stt_don_tiep')
                ->where($dieu_kien)
                ->whereDate('thoi_gian_phat', '=', $today)
                ->orderBy('id', 'desc')
                ->first();
                
        return $data;   
    }
    
    
}