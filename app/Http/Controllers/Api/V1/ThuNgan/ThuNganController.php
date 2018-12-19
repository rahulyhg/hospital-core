<?php

namespace App\Http\Controllers\Api\V1\ThuNgan;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\SoThuNganService;
use App\Http\Requests\SoThuNganFormRequest;

class ThuNganController extends APIController
{
  public function __construct(SoThuNganService $soThuNganService)
  {
      
    $this->soThuNganService = $soThuNganService;
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

}
