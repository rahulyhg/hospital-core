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
        $isNumericId = is_numeric($dmdvId);
        
        if($isNumericId) {
            $data = $this->dmdvService->getDmdvById($dmdvId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function createDanhMucDichVu(DanhMucDichVuFormRequest $request)
    {
        $input = $request->all();
        
        $id = $this->dmdvService->createDanhMucDichVu($input);
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
            $isNumericId = is_numeric($dmdvId);
            $input = $request->all();
            
            if($isNumericId) {
                $this->dmdvService->updateDanhMucDichVu($dmdvId, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function deleteDanhMucDichVu($dmdvId)
    {
        $isNumericId = is_numeric($dmdvId);
        
        if($isNumericId) {
            $this->dmdvService->deleteDanhMucDichVu($dmdvId);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function getYLenhByLoaiNhom($loaiNhom)
    {
        $isNumeric = is_numeric($loaiNhom);
        
        if($isNumeric) {
            $data = $this->dmdvService->getYLenhByLoaiNhom($loaiNhom);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
}