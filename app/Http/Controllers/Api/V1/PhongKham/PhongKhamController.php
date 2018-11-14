<?php
namespace App\Http\Controllers\Api\V1\PhongKham;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\HsbaKhoaPhongService;

class PhongKhamController extends APIController
{
    public function __construct(HsbaKhoaPhongService $hsbaKhoaPhongService)
    {
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
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
}