<?php

namespace App\Http\Controllers\Api\V1\ThuNgan;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\SoThuNganService;
use App\Services\VienPhiService;
use App\Services\YLenhService;
use App\Http\Requests\SoThuNganFormRequest;
use App\Http\Requests\ThongTinVienPhiFormRequest;

class ThuNganController extends APIController
{
    public function __construct
    (
        SoThuNganService $soThuNganService,
        VienPhiService $vienPhiService,
        YLenhService $yLenhService
    )
    {
        $this->soThuNganService = $soThuNganService;
        $this->vienPhiService = $vienPhiService;
        $this->yLenhService = $yLenhService;
    }
  
    public function createSoThuNgan(SoThuNganFormRequest $request)
    {
        try {
            $input = $request->all();
            $data = $this->soThuNganService->createSoThuNgan($input);
            $this->setStatusCode(201);
            return $this->respond($data);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }

    public function getListDichVuByHsbaId($hsbaId)
    {
        if(is_numeric($hsbaId)) {
            $data = $this->yLenhService->getYLenhByHsbaId($hsbaId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
  
//   public function getThongTinVienPhi(ThongTinVienPhiFormRequest $request)
//   {
//     try 
//     {
//       $input = $request->all();
//       $data = $this->vienPhiService->getThongTinVienPhi($input);
//       $this->setStatusCode(201);
//       return $this->respond($data);
//     } catch (\Exception $ex) {
//         return $this->respondInternalError($ex->getMessage());
//     }
//   }

}
