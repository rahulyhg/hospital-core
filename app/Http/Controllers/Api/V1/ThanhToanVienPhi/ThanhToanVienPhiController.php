<?php
namespace App\Http\Controllers\Api\V1\ThanhToanVienPhi;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\VienPhiService;
use App\Services\YLenhService;
use App\Http\Requests\ThanhToanVienPhiFormRequest;

class ThanhToanVienPhiController extends APIController
{
    public function __construct
    (
        VienPhiService $vienPhiService,
        YLenhService $yLenhService
    )
    {
        $this->vienPhiService = $vienPhiService;
        $this->yLenhService = $yLenhService;
    }

    public function getListVienPhiByHsbaId($hsbaId)
    {
        $data = $this->vienPhiService->getListVienPhiByHsbaId($hsbaId);
        if(!$data) {
            $this->setStatusCode(400);
            $data = [];
        }
        return $this->respond($data);
    }    
    
    public function getListYLenhByVienPhiId($vienPhiId,$keyWords)
    {
        $data = $this->yLenhService->getListYLenhByVienPhiId($vienPhiId,$keyWords);
        
        if(!$data) {
            $this->setStatusCode(400);
            $data = [];
        }
        return $this->respond($data);
    }
    
    public function updateYLenhById($yLenhId,ThanhToanVienPhiFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($yLenhId);
            $input = $request->all();
            if($isNumericId) {
                $this->yLenhService->updateYLenhById($yLenhId, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        } 
    }
    
    public function createVienPhi(Request $request)
    {   
        try 
        {
            $data = $this->vienPhiService->createVienPhi($request);
            $this->setStatusCode(201);
            return $this->respond($data);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }    
}