<?php

namespace App\Repositories\SttDontiep;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\RedSttDontiep;
use Carbon\Carbon;

class RedSttDontiepRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return RedSttDontiep::class;
    }
    public function getSTTTK()
    {
        $Nowdate = Carbon::now();
            $result = $this->model->where('loai_stt', '=', 'D')->whereDay('thoi_gian_phat', '=' ,$Nowdate->day)->orderBy('id', 'desc')->first();
            $stt_dontiep_tk = $result['so_thu_tu'];
            //$stt_dontiep_bt = 1;
            //return $result;
            if($stt_dontiep_tk == 0)
            {
                $attributes = ['loai_stt' => 'D',
                                    'so_thu_tu' => 1,
                                    'trang_thai' => 1,
                                    'thoi_gian_phat' => Carbon::now()->toDateTimeString(),
                                    'thoi_gian_goi' =>'',
                                    'thoi_gian_ket_thuc' =>'',
                                    'ma_so_kiosk' => 1,
                                    'id_phong' => 1,
                                    'id_benh_vien' => 1,
                                    'thong_tin_so_bo' =>''
                                    ];
                $this->model->create(['loai_stt' => 'A','so_thu_tu' => 1,'trang_thai' => 1,'thoi_gian_phat' => Carbon::now()->toDateTimeString(),'thoi_gian_goi' =>null,'thoi_gian_ket_thuc' =>null,'ma_so_kiosk' => 1,'id_phong' => 1,'id_benh_vien' => 1,'thong_tin_so_bo' =>'']);
                $stt =sprintf('%03d', 1);
                return $stt_dontiep_tk = "D".$stt;
                
            }
            else
            {
                $stt_dontiep_tk += 1;
                $attributes = ['loai_stt' => 'D',
                                'so_thu_tu' => $stt_dontiep_tk,
                                'trang_thai' => 1,
                                'thoi_gian_phat' => Carbon::now()->toDateTimeString(),
                                'thoi_gian_goi' =>null,
                                'thoi_gian_ket_thuc' =>null,
                                'ma_so_kiosk' => 1,
                                'id_phong' => 1,
                                'id_benh_vien' => 1,
                                'thong_tin_so_bo' =>''
                                    ];
                $this->model->create($attributes);
                $stt = sprintf('%03d',$stt_dontiep_tk);                    
                return $stt_dontiep_tk = $result['loai_stt'].$stt;
            }
    }
    public function getSTTKM($age)
    {
        if($age <= 6 || $age >= 70)
        {
            return $this->getCurrentSTTUT();
        }
        else
        {
            return $this->getCurrentSTTBT();
        }
    }
    public function getCurrentSTTUT()
    {
        $Nowdate = Carbon::now();
            $result = $this->model->where('loai_stt', '=', 'A')->whereDay('thoi_gian_phat', '=' ,$Nowdate->day)->orderBy('id', 'desc')->first();
            $stt_dontiep_ut = $result['so_thu_tu'];
            //$stt_dontiep_bt = 1;
            //return $result;
            if($stt_dontiep_ut == 0)
            {
                $attributes = ['loai_stt' => 'A',
                                    'so_thu_tu' => 1,
                                    'trang_thai' => 1,
                                    'thoi_gian_phat' => Carbon::now()->toDateTimeString(),
                                    'thoi_gian_goi' =>'',
                                    'thoi_gian_ket_thuc' =>'',
                                    'ma_so_kiosk' => 1,
                                    'id_phong' => 1,
                                    'id_benh_vien' => 1,
                                    'thong_tin_so_bo' =>''
                                    ];
                $this->model->create(['loai_stt' => 'A','so_thu_tu' => 1,'trang_thai' => 1,'thoi_gian_phat' => Carbon::now()->toDateTimeString(),'thoi_gian_goi' =>null,'thoi_gian_ket_thuc' =>null,'ma_so_kiosk' => 1,'id_phong' => 1,'id_benh_vien' => 1,'thong_tin_so_bo' =>'']);
                $stt =sprintf('%03d', 1);
                return $stt_dontiep_ut = "A".$stt;
                
            }
            else
            {
                $stt_dontiep_ut += 1;
                $attributes = ['loai_stt' => 'A',
                                'so_thu_tu' => $stt_dontiep_ut,
                                'trang_thai' => 1,
                                'thoi_gian_phat' => Carbon::now()->toDateTimeString(),
                                'thoi_gian_goi' =>null,
                                'thoi_gian_ket_thuc' =>null,
                                'ma_so_kiosk' => 1,
                                'id_phong' => 1,
                                'id_benh_vien' => 1,
                                'thong_tin_so_bo' =>''
                                    ];
                $this->model->create($attributes);
                $stt = sprintf('%03d',$stt_dontiep_ut);                    
                return $stt_dontiep_ut = $result['loai_stt'].$stt;
            }
    }
    public function insertCurrentSTTUT(array $attributes)
    {
        $this->model->create($attributes);
    }
    public function getCurrentSTTDKT()
    {
        
    }
    public function getCurrentSTTBT()
    {       $Nowdate = Carbon::now();
            $result = $this->model->where('loai_stt', '=', 'C')->whereDay('thoi_gian_phat', '=' ,$Nowdate->day)->orderBy('id', 'desc')->first();
            $stt_dontiep_bt = $result['so_thu_tu'];
            //$stt_dontiep_bt = 1;
            //return $result;
            if($stt_dontiep_bt == 0)
            {
                $attributes = ['loai_stt' => 'C',
                                    'so_thu_tu' => 1,
                                    'trang_thai' => 1,
                                    'thoi_gian_phat' => Carbon::now()->toDateTimeString(),
                                    'thoi_gian_goi' =>'',
                                    'thoi_gian_ket_thuc' =>'',
                                    'ma_so_kiosk' => 1,
                                    'id_phong' => 1,
                                    'id_benh_vien' => 1,
                                    'thong_tin_so_bo' =>''
                                    ];
                $this->model->create(['loai_stt' => 'C','so_thu_tu' => 1,'trang_thai' => 1,'thoi_gian_phat' => Carbon::now()->toDateTimeString(),'thoi_gian_goi' =>null,'thoi_gian_ket_thuc' =>null,'ma_so_kiosk' => 1,'id_phong' => 1,'id_benh_vien' => 1,'thong_tin_so_bo' =>'']);
                $stt =sprintf('%03d', 1);
                return $stt_dontiep_bt = "C".$stt;
                
            }
            else
            {
                $stt_dontiep_bt += 1;
                $attributes = ['loai_stt' => 'C',
                                'so_thu_tu' => $stt_dontiep_bt,
                                'trang_thai' => 1,
                                'thoi_gian_phat' => Carbon::now()->toDateTimeString(),
                                'thoi_gian_goi' =>null,
                                'thoi_gian_ket_thuc' =>null,
                                'ma_so_kiosk' => 1,
                                'id_phong' => 1,
                                'id_benh_vien' => 1,
                                'thong_tin_so_bo' =>''
                                    ];
                $this->model->create($attributes);
                $stt = sprintf('%03d',$stt_dontiep_bt);                    
                return $stt_dontiep_bt = $result['loai_stt'].$stt;
            }
    }
    public function insertCurrentSTTBT(array $attributes)
    {
        $this->model->create($attributes);
    }
    public function getCurrentSTTTK()
    {
        
    }

}