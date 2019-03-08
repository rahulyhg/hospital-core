<?php
namespace App\Http\Controllers\Api\V1\NoiTru;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\PhongBenhService;

class PhongBenhController extends APIController
{
    public function __construct(PhongBenhService $phongBenhService)
    {
        $this->phongBenhService = $phongBenhService;
    }

    public function getListPhongBenh(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keyWords = $request->query('keyWords', '');
        $data = $this->phongBenhService->getList($limit, $page, $keyWords);
        return $this->respond($data);
    }
    
    public function createPhongBenh(Request $request)
    {
        $input = $request->all();
        $this->phongBenhService->create($input);
        return $this->respond([]);
    }
    
    public function updatePhongBenh($id, Request $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->phongBenhService->update($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }    
    
    public function deletePhongBenh($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $this->phongBenhService->delete($id);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function getPhongBenhById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->phongBenhService->getById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }    

}