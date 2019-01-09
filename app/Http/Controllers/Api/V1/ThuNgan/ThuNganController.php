<?php

namespace App\Http\Controllers\Api\V1\ThuNgan;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\SoThuNganService;
use App\Services\VienPhiService;
use App\Http\Requests\SoThuNganFormRequest;
use App\Http\Requests\ThongTinVienPhiFormRequest;

class ThuNganController extends APIController
{
  public function __construct
  (
    SoThuNganService $soThuNganService,
    VienPhiService $vienPhiService
  )
  {
      
    $this->soThuNganService = $soThuNganService;
    $this->vienPhiService = $vienPhiService;
  }
  
  public function createSoThuNgan(SoThuNganFormRequest $request)
  {
    try 
    {
      $input = $request->all();
      $data = $this->soThuNganService->createSoThuNgan($input);
      $this->setStatusCode(201);
      return $this->respond($data);
    } catch (\Exception $ex) {
        return $this->respondInternalError($ex->getMessage());
    }
  }
  
  public function getThongTinVienPhi(ThongTinVienPhiFormRequest $request)
  {
    try 
    {
      $input = $request->all();
      $data = $this->vienPhiService->getThongTinVienPhi($input);
      $this->setStatusCode(201);
      return $this->respond($data);
    } catch (\Exception $ex) {
        return $this->respondInternalError($ex->getMessage());
    }
  }

}
