<?php
namespace App\Http\Controllers\Api\V1\Kho;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\KhoService;
use App\Http\Requests\CreateKhoFormRequest;
use App\Http\Requests\UpdateKhoFormRequest;

class KhoController extends APIController
{
    public function __construct(KhoService $khoService)
    {
        $this->khoService = $khoService;
    }
    public function getListKho(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keyWords = $request->query('keyWords', '');
        $benhVienId = $request->query('benhVienId', '');
        $data = $this->khoService->getListKho($limit, $page, $keyWords, $benhVienId);
        return $this->respond($data);
    }
    
    public function createKho(CreateKhoFormRequest $request)
    {
        $input = $request->all();
        
        $id = $this->khoService->createKho($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function updateKho($id,UpdateKhoFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->khoService->updateKho($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }    
    
    public function deleteKho($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $this->khoService->deleteKho($id);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function getKhoById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->khoService->getKhoById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }    
}