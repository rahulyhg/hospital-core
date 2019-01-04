<?php

namespace App\Services;

use App\Models\YLenh;
use App\Repositories\YLenh\YLenhRepository;
use App\Repositories\PhieuYLenh\PhieuYLenhRepository;
use Illuminate\Http\Request;
use Validator;
use DB;
use Carbon\Carbon;

class YLenhService {
    const PHIEU_DIEU_TRI = 3;
    
    public function __construct(YLenhRepository $yLenhRepository, PhieuYLenhRepository $phieuYLenhRepository)
    {
        $this->yLenhRepository = $yLenhRepository;
        $this->phieuYLenhRepository = $phieuYLenhRepository;
    }

    public function saveYLenh(array $input)
    {
        $array = [];
        
        $result = DB::transaction(function() use ($input, $array) {
            try {
                //insert table phieu_y_lenh
                $phieuYLenhParams = $input;
                $phieuYLenhParams = array_except($phieuYLenhParams, ['hsba_khoa_phong_id', 'data', 'doi_tuong_benh_nhan']);
                $phieuYLenhParams['loai_phieu_y_lenh'] = self::PHIEU_DIEU_TRI;
                $phieuYLenhParams['trang_thai'] = 0;
                $phieuYLenhId = $this->phieuYLenhRepository->getPhieuYLenhId($phieuYLenhParams);
                
                //insert table y_lenh
                if($input['data']) {
                    foreach($input['data'] as $value) {
                        // if($item['children']) {
                        //     foreach($item['children'] as $value) {
                                $array[] = [
                                    'vien_phi_id'           => $input['vien_phi_id'],
                                    'phieu_y_lenh_id'       => $phieuYLenhId,
                                    'doi_tuong_benh_nhan'   => $input['doi_tuong_benh_nhan'],
                                    'khoa_id'               => $input['khoa_id'],
                                    'phong_id'              => $input['phong_id'],
                                    'ma'                    => $value['ma'],
                                    'ten'                   => $value['ten'],
                                    'ten_bhyt'              => $value['ten_bhyt'],
                                    'ten_nuoc_ngoai'        => $value['ten_nuoc_ngoai'],
                                    'trang_thai'            => 0,
                                    'gia'                   => $value['gia'],
                                    'gia_bhyt'              => $value['gia_bhyt'],
                                    'gia_nuoc_ngoai'        => $value['gia_nuoc_ngoai'],
                                    'so_luong'              => $value['so_luong'],
                                    'loai_y_lenh'           => $value['loai_nhom'],
                                    'thoi_gian_chi_dinh'    => Carbon::now()->toDateTimeString(),
                                ];
                        //     }
                        // }
                        
                        // $arrTemp = explode('---', $value);
                        // $arr = explode('--', $arrTemp[1]);
                        
                        // $array[] = [
                        //     'vien_phi_id'           => $input['vien_phi_id'],
                        //     'phieu_y_lenh_id'       => $phieuYLenhId,
                        //     'doi_tuong_benh_nhan'   => $input['doi_tuong_benh_nhan'],
                        //     'khoa_id'               => $input['khoa_id'],
                        //     'phong_id'              => $input['phong_id'],
                        //     'ma'                    => $arr[0],
                        //     'ten'                   => $arr[1],
                        //     'trang_thai'            => 0,
                        //     'so_luong'              => 1,
                        //     'loai_y_lenh'           => $arrTemp[0],
                        //     'thoi_gian_chi_dinh'    => Carbon::now()->toDateTimeString(),
                        // ];
                    }
                }
                $this->yLenhRepository->saveYLenh($array);
                
                return true;
            } catch (\Exception $ex) {
                throw $ex;
            }
        });
        
        return $result;
    }
    
    public function getLichSuYLenh(array $input)
    {
        $result = $this->yLenhRepository->getLichSuYLenh($input);
                            
        return $result;
    }
}