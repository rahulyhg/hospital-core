<?php

namespace App\Services;

use App\Repositories\PhongBenhRepository;
use App\Repositories\GiuongBenhRepository;
use Illuminate\Http\Request;
use Validator;
use DB;

class PhongBenhService {
  
    // Trang thai giuong benh
    const DANG_SU_DUNG = 1;
    const KHONG_SU_DUNG = 0;
  
    public function __construct(PhongBenhRepository $phongBenhRepository, GiuongBenhRepository $giuongBenhRepository)
    {
        $this->phongBenhRepository = $phongBenhRepository;
        $this->giuongBenhRepository = $giuongBenhRepository;
    }
    
    public function getList($limit, $page, $keyWords)
    {
        $data = $this->phongBenhRepository->getList($limit, $page, $keyWords);
        return $data;
    }
    
    public function create(array $input)
    {
        $result = DB::transaction(function () use ($input) {
            try {
                // 1. Add new room
                $input['con_trong'] = $input['so_luong_giuong'];
                $id = $this->phongBenhRepository->create($input);
                
                // 2. Add new bed by number bed of room
                for($i = 1; $i <= $input['so_luong_giuong']; $i++) {
                    $giuongParams = null;
                    $giuongParams['phong_id'] = $id;
                    $giuongParams['stt'] = $i;
                    $giuongParams['tinh_trang'] = self::KHONG_SU_DUNG;
                    $this->giuongBenhRepository->create($giuongParams);
                }
            }
            catch (\Exception $ex) {
                 throw $ex;
            }
        });
        return $result; 
    }
    
    public function getById($id)
    {
        $data = $this->phongBenhRepository->getById($id);
        return $data;
    }
    
    public function update($id, array $input)
    {
        $this->phongBenhRepository->update($id, $input);
    }
    
    public function delete($id)
    {
        $result = DB::transaction(function () use ($id) {
            try {
                // 1. Delete bed by room id
                $this->giuongBenhRepository->deleteByRoomId($id);
                
                // 2. Delete room
                $this->phongBenhRepository->delete($id);
            }
            catch (\Exception $ex) {
                 throw $ex;
            }
        });
        return $result;
    }
}