<?php
namespace App\Http\Controllers\Api\V1\PhongKham;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\HsbaKhoaPhongService;
use App\Services\DieuTriService;

class PhongKhamController extends APIController
{
    public function __construct(HsbaKhoaPhongService $hsbaKhoaPhongService, DieuTriService $dieuTriService)
    {
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
        $this->dieuTriService = $dieuTriService;
    }
    
    public function updateHsbaKhoaPhong($hsbaKhoaPhongId, Request $request)
    {
        try {
            if(is_numeric($hsbaKhoaPhongId)) {
                $input = $request->all();
                $this->hsbaKhoaPhongService->updateHsbaKhoaPhong($hsbaKhoaPhongId, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }
    
    public function getHsbaKhoaPhongById($hsbaKhoaPhongId)
    {
        $isNumeric = is_numeric($hsbaKhoaPhongId);
        
        if($isNumeric) {
            $data = $this->hsbaKhoaPhongService->getHsbaKhoaPhongById($hsbaKhoaPhongId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function updateInfoDieuTri(Request $request)
    {
        try 
        {
            $this->dieuTriService->updateInfoDieuTri($request);
            $this->setStatusCode(201);
            
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }
}