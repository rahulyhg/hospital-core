<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\KhoaService;
use App\Services\PhongService;
use App\Http\Requests\KhoaFormRequest;

class KhoaPhongController extends APIController
{
    const trang_thai_phong_moi_tao = 0;
    
    public function __construct(KhoaService $khoaService, PhongService $phongService)
    {
        $this->khoaService = $khoaService;
        $this->phongService = $phongService;
    }
    
    public function createKhoa($benhVienId, KhoaFormRequest $request)
    {
        $input = $request->all();
        if(is_numeric($benhVienId))
        {
            $id = $this->khoaService->createKhoa($benhVienId, $input);
            if($id) {
                $this->setStatusCode(201);
            } else {
                $this->setStatusCode(400);
            }
        }
        else {
            $this->setStatusCode(400);
        }
        return $this->respond([]);
    }
    
    public function createPhong($khoaId, Request $request)
    {
        $input = $request->all();
        //if(empty($input['trang_thai'])) $input['trang_thai'] = self::trang_thai_phong_moi_tao;
        
        if(is_numeric($khoaId))
        {
            $id = $this->phongService->createPhong($khoaId, $input);
            if($id) {
                $this->setStatusCode(201);
            } else {
                $this->setStatusCode(400);
            }
        }
        else {
            $this->setStatusCode(400);
        }
        return $this->respond([]);
    }

}
