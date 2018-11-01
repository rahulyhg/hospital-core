<?php
namespace App\Http\Controllers\Api\V1\DanhMuc;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\DanhMucDichVuService;
use App\Http\Requests\DanhMucDichVuFormRequest;

class DanhMucController extends APIController
{
    public function __construct(DanhMucDichVuService $dmdvService)
    {
        $this->dmdvService = $dmdvService;
    }
    
    public function getListDanhMucDichVu(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        
        $data = $this->dmdvService->getListDanhMucDichVu($limit, $page);
        return $this->respond($data);
    }
    
    public function getDmdvById($dmdvId)
    {
        if(is_numeric($dmdvId)) {
            $data = $this->dmdvService->getDmdvById($dmdvId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function createDanhMucDichVu(DanhMucDichVuFormRequest $request)
    {
        $id = $this->dmdvService->createDanhMucDichVu($request);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function updateDanhMucDichVu($dmdvId, DanhMucDichVuFormRequest $request)
    {
        try {
            if(is_numeric($dmdvId)) {
                $this->dmdvService->updateDanhMucDichVu($dmdvId, $request);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function deleteDanhMucDichVu($dmdvId)
    {
        if(is_numeric($dmdvId)) {
            $this->dmdvService->deleteDanhMucDichVu($dmdvId);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
}