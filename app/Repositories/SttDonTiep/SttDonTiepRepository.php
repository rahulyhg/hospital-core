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
    
    public function goiSttDonTiep($request)
    {
        $loaiStt = $request->query('loaiStt', 'C');
        $phongId = $request->query('phongId', 1);
        $benhVienId = $request->query('benhVienId', 1);
        $quaySo = $request->query('quaySo', 1);
        $authUsersId = $request->query('authUsersId', 1);
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
        
        $id = $result['id'];
                            
        $attributes = ['trang_thai' => 2,
                        'thoi_gian_goi' => Carbon::now()->toDateTimeString(),
                        'quay_so' => $quaySo,
                        'auth_users_id' => $authUsersId
                    ];
        
        $this->model->where('id', '=', $id)->update($attributes);
        
        $data = $this->model->findOrFail($id);
        
        return $data;
    }
    
    public function loadSttDonTiep($request)
    {
        $phongId = $request->query('phongId', 1);
        $benhVienId = $request->query('benhVienId', 1);
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
    
    public function getInfoPatientByStt($stt, $phongId, $benhVienId)
    {
        $today = Carbon::today();
        
        $where = [
            'loai_stt'      => $stt[0],
            'so_thu_tu'     => (int)substr($stt, 1, 4),
            'phong_id'      => $phongId,
            'benh_vien_id'  => $benhVienId
        ];
        
        $data = DB::table('stt_don_tiep')
                ->where($where)
                ->whereDate('thoi_gian_phat', '=', $today)
                ->orderBy('id', 'desc')
                ->first();
                
        return $data;   
    }
    
}