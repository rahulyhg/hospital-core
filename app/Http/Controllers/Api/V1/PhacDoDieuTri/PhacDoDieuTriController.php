<?php
namespace App\Http\Controllers\Api\V1\PhacDoDieuTri;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\PhacDoDieuTriService;

class PhacDoDieuTriController extends APIController
{
    public function __construct(PhacDoDieuTriService $pddtService)
    {
        $this->pddtService = $pddtService;
    }
    
    public function getListPhacDoDieuTri(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keyword = $request->query('keyword', '');
        
        $data = $this->pddtService->getListPhacDoDieuTri($limit, $page, $keyword);
        return $this->respond($data);
    }
    
    public function getPddtById($pddtId)
    {
        $isNumeric = is_numeric($pddtId);
        
        if($isNumeric) {
            $data = $this->pddtService->getPddtById($pddtId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function savePddt($pddtId, Request $request)
    {
        try {
            $isNumeric = is_numeric($pddtId);
            $input = $request->all();
            
            if($isNumeric) {
                $this->pddtService->savePhacDoDieuTri($pddtId, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
}