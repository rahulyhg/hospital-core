<?php
namespace App\Http\Controllers\Api\V1\HanhChinh;

use Illuminate\Http\Request;
use App\Services\HanhChinhService;
use App\Services\DieuTriService;
use App\Http\Controllers\Api\V1\APIController;
use Carbon\Carbon;

class HanhChinhController extends APIController {
    public function __construct(
        HanhChinhService $hanhChinhService,
        DieuTriService $dieuTriService
    )
    {
        $this->hanhChinhService = $hanhChinhService;
        $this->dieuTriService = $dieuTriService;
    }
    
    public function luuNhapKhoa(Request $request)
    {
        $input = $request->all();
        $phieuDieuTri = $this->dieuTriService->getPhieuDieuTri($input);
        if($phieuDieuTri) {
            $input['dieu_tri_id'] = $phieuDieuTri->id;
            $bool = $this->hanhChinhService->luuNhapKhoa($input);
            $this->setStatusCode(201);
            return $this->respond($bool);
        } else {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
    }
}