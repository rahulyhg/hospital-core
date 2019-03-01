<?php
namespace App\Http\Controllers\Api\V1\PhieuNhapKho;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\PhieuKhoService;

class PhieuNhapKhoController extends APIController
{
    public function __construct(PhieuKhoService $phieuKhoService)
    {
        $this->phieuKhoService = $phieuKhoService;
    }
    
    public function createPhieuNhapKho(Request $request)
    {
        $input = $request->all();
        $this->phieuKhoService->createPhieuKho($input);
        return $this->respond([]);
    }
}