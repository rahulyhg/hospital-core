<?php
namespace App\Http\Controllers\Api\V1\PhieuYeuCau;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\TheKhoService;
use App\Services\PhieuKhoService;

class PhieuYeuCauController extends APIController
{
    public function __construct(theKhoService $theKhoService,PhieuKhoService $phieuKhoService)
    {
        $this->theKhoService = $theKhoService;
        $this->phieuKhoService = $phieuKhoService;
    }
    
    public function getTonKhaDungById($id,$khoId)
    {
        $data = $this->theKhoService->getTonKhaDungById($id,$khoId);
        return $this->respond($data);
    }
    
    public function createPhieuYeuCau(Request $request)
    {
        $input = $request->all();
        $this->phieuKhoService->createPhieuYeuCau($input);
        return $this->respond([]);
    }    
}