<?php
namespace App\Repositories\SttDonTiep;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\SttDonTiep;
use Carbon\Carbon;
//use Illuminate\Support\Facades\Redis;

class SttDonTiepRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return SttDonTiep::class;
    }
    
    public function getSttDonTiep($loaiStt, $maSoKiosk, $phongId, $benhVienId, $data)
    {
        $today = Carbon::today();
        
        $where = [
            ['loai_stt', '=', $loaiStt],
            ['phong_id', '=', $phongId],
            ['benh_vien_id', '=', $benhVienId]
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
        
        $attributes = ['loai_stt' => $loaiStt,
                        'so_thu_tu' => $soThuTu,
                        'trang_thai' => 1,
                        'thoi_gian_phat' => Carbon::now()->toDateTimeString(),
                        'thoi_gian_goi' => null,
                        'thoi_gian_ket_thuc' => null,
                        'ma_so_kiosk' => $maSoKiosk,
                        'phong_id' => $phongId,
                        'benh_vien_id' => $benhVienId,
                        'thong_tin_so_bo' => $data ? json_encode($data) : null,
                        'quay_so' => null,
                        'auth_users_id' => null
                    ];
                    
        $this->model->create($attributes);
        
        $stt = $soThuTu;
        
        return $stt;
    }
    
    public function getSttDangPhucVu($loaiStt, $phongId, $benhVienId) 
    {
        $today = Carbon::today();
        
        $where = [
            ['loai_stt', '=', $loaiStt],
            ['phong_id', '=', $phongId],
            ['benh_vien_id', '=', $benhVienId]
        ];
        
        $result = $this->model->where($where)
                            ->whereBetween('thoi_gian_goi', [Carbon::parse($today)->startOfDay(), Carbon::parse($today)->endOfDay()])
                            ->orderBy('id', 'desc')
                            ->first();
        
        if($result)                    
            $sttDangPhucVu = $result['so_thu_tu'];
        else
            $sttDangPhucVu = '';
        
        return $sttDangPhucVu;
    }
    
    public function calcTime($sttDangPhucVu, $loaiStt, $phongId, $benhVienId)
    {
        $today = Carbon::today();
        
        $where = [
            ['loai_stt', '=', $loaiStt],
            ['phong_id', '=', $phongId],
            ['benh_vien_id', '=', $benhVienId],
            ['trang_thai', '=', 1],
            ['so_thu_tu', '>', $sttDangPhucVu]
        ];
        
        $result = $this->model->where($where)
                            ->whereBetween('thoi_gian_phat', [Carbon::parse($today)->startOfDay(), Carbon::parse($today)->endOfDay()])
                            ->get();
        
        if($result) {
            $seconds = 0;
            foreach($result as $item) {
                if($item['thong_tin_so_bo'])
                    $seconds += 30;
                else
                    $seconds += 180;
            }
            
            $time = gmdate('H:i', $seconds);
            $arrTime = explode(':', $time);
            
            $thoiGianCho = ($arrTime[0] != '00' ? $arrTime[0] . ' giờ ' . $arrTime[1] . ' phút' : $arrTime[1] . ' phút');
        } else {
            $thoiGianCho = '01 phút';
        }
        
        return $thoiGianCho;
    }
    
    public function goiSttDonTiep(array $input)
    {
        $loaiStt = $input['loaiStt'];
        $phongId = $input['phongId'];
        $benhVienId = $input['benhVienId'];
        $quaySo = $input['quaySo'];
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
                            'quay_so' => $quaySo,
                            'auth_users_id' => $authUsersId
                        ];
            
            $this->model->where('id', '=', $id)->update($attributes);
            
            $data = $this->model->findOrFail($id);
            
            //$redis = Redis::connection();
            //$redis->hmset('sttDonTiep', 'lastCall_'.$phongId.'_'.$benhVienId, Carbon::now()->toDateTimeString());
        } else {
            $data = null;
        }
        
        return $data;
    }
    
    public function loadSttDonTiep(array $input)
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
    
    public function finishSttDonTiep($sttId)
    {
        $today = Carbon::today();
        
        $attributes = ['trang_thai' => 3,
                        'thoi_gian_ket_thuc' => Carbon::now()->toDateTimeString()
                    ];
                    
        $this->model->where('id', '=', $sttId)->update($attributes);
    }
    
    public function countSttDonTiep(array $input)
    {
        $phongId = $input['phongId'];
        $benhVienId = $input['benhVienId'];
        $today = Carbon::today();
        
        $data = ['A' => 0,
                'B' => 0,
                'C' => 0
        ];
        
        $result = $this->model->select('loai_stt', DB::raw('count(loai_stt) as total'))
                            ->where('trang_thai', '=', 1)
                            ->whereBetween('thoi_gian_phat', [Carbon::parse($today)->startOfDay(), Carbon::parse($today)->endOfDay()])
                            ->groupBy('loai_stt')
                            ->get();
                 
        if($result) {
            foreach($result as $item) {
                $data[$item->loai_stt] = $item->total;
            }
        }
                            
        return $data;
    }
    
}