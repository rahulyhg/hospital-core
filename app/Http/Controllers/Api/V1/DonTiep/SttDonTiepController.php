<?php
namespace App\Http\Controllers\Api\V1\DonTiep;

use Illuminate\Http\Request;
use App\Services\SttDonTiepService;
use App\Services\AuthService;
use App\Http\Controllers\Api\V1\APIController;

class SttDonTiepController extends APIController
{
    public function __construct(SttDonTiepService $sttDonTiepService, AuthService $authService)
    {
        $this->service = $sttDonTiepService;
        $this->authService = $authService;
    }
    
    public function makeSttDonTiepWhenScanCard(Request $request)
    {
        if($request['cardCode'] !== null && $this->checkExistParam($request['phongId'], $request['benhVienId']) && $request['maSoKiosk'] !== null) {
            $data = $this->service->makeSttDonTiepWhenScanCard($request);
            return $this->respond($data);
        } else {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
    }
    
    public function scanCard(Request $request)
    {
        if($request['cardCode'] !== null) {
            $data = $this->service->scanCard($request['cardCode']);
            return $this->respond($data);
        } else {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
    }
    
    public function getSttDonTiep(Request $request)
    {
        //sai tham so $request['loaiStt']
        if(!in_array($request['loaiStt'], ['A', 'B', 'C'])){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        if($this->checkExistParam($request['phongId'], $request['benhVienId']) && $request['maSoKiosk'] !== null) {
            $data = $this->service->getSttDonTiep($request);
            return $this->respond($data);
        } else {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
    }
    
    public function goiSttDonTiep(Request $request)
    {
        $loaiStt = $request->query('loaiStt');
        $phongId = $request->query('phongId');
        $benhVienId = $request->query('benhVienId');
        $quaySo = $request->query('quaySo');
        $authUsersId = $request->query('authUsersId');
        
        //sai tham so $loaiStt
        if(!in_array($loaiStt, ['A', 'B', 'C'])){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        //khong co tham so $phongId, $benhVienId, $quaySo
        if($phongId === null || $benhVienId === null || $quaySo === null){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        //khong ton tai $authUsersId
        $bool = $this->authService->getUserById($authUsersId);
        if(!$bool){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        $data = $this->service->goiSttDonTiep($request);
        
        //ko co du lieu stt
        if($data === null)
            $this->setStatusCode(404);
        
        return $this->respond($data);
    }
    
    public function loadSttDonTiep(Request $request)
    {
        if($this->checkExistParam($request['phongId'], $request['benhVienId'])) {
            $data = $this->service->loadSttDonTiep($request);
        } else {
            $data = null;
            $this->setStatusCode(400);
        }
        
        return $this->respond($data);
    }
    
    public function finishSttDonTiep($sttId)
    {
        if(is_numeric($sttId)) {
            $this->service->finishSttDonTiep($sttId);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function countSttDonTiep(Request $request)
    {
        if($this->checkExistParam($request['phongId'], $request['benhVienId'])) {
            $data = $this->service->countSttDonTiep($request);
        } else {
            $data = null;
            $this->setStatusCode(400);
        }
        
        return $this->respond($data);
    }
    
    public function checkExistParam($phongId, $benhVienId)
    {
        if($phongId === null || $benhVienId === null)
            return false;
        else
            return true;
    }
}