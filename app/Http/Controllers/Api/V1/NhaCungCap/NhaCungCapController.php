<?php
namespace App\Http\Controllers\Api\V1\NhaCungCap;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\NhaCungCapService;
use App\Http\Requests\AuthUserFormRequest;
use App\Http\Requests\UpdateAuthUsersFormRequest;

class NhaCungCapController extends APIController
{
    public function __construct(NhaCungCapService $nhaCungCapService)
    {
        $this->nhaCungCapService = $nhaCungCapService;
    }

    public function getListNhaCungCap(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keyWords = $request->query('keyWords', '');
        $data = $this->nhaCungCapService->getListNhaCungCap($limit, $page, $keyWords);
        return $this->respond($data);
    }
    
    public function createNhaCungCap(Request $request)
    {
        $input = $request->all();
        
        $id = $this->nhaCungCapService->createNhaCungCap($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function updateNhaCungCap($id,Request $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->nhaCungCapService->updateNhaCungCap($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }    
    
    public function deleteNhaCungCap($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $this->nhaCungCapService->deleteNhaCungCap($id);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function getNhaCungCapById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->nhaCungCapService->getNhaCungCapById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }    

}