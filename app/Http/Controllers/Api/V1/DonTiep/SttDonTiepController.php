<?php
namespace App\Http\Controllers\Api\V1\DonTiep;

use Illuminate\Http\Request;
use App\Services\SttDonTiepService;
use App\Services\AuthService;
use App\Http\Controllers\Api\V1\APIController;

class SttDonTiepController extends APIController
{
    const LOAI_STT = ['A', 'B', 'C'];
    
    public function __construct(SttDonTiepService $sttDonTiepService, AuthService $authService)
    {
        $this->service = $sttDonTiepService;
        $this->authService = $authService;
    }
    
    public function makeSttDonTiepWhenScanCard(Request $request)
    {
        $isCardCode = $request['cardCode'] ? true : false;
        $isExistParam = $this->checkExistParam($request['phongId'], $request['benhVienId']);
        $isMaSoKiosk = $request['maSoKiosk'] ? true : false;
        $input = $request->all();
        
        if($isCardCode && $isExistParam && $isMaSoKiosk) {
            $data = $this->service->makeSttDonTiepWhenScanCard($input);
            return $this->respond($data);
        } else {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
    }
    
    public function scanCard(Request $request)
    {
        $isCardCode = $request['cardCode'] ? true : false;
        
        if($isCardCode) {
            $data = $this->service->scanCard($request['cardCode']);
            return $this->respond($data);
        } else {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
    }
    
    public function getSttDonTiep(Request $request)
    {
        $input = $request->all();
        $isValidLoaiStt = in_array($input['loaiStt'], self::LOAI_STT);
        
        //sai tham so $loaiStt
        if(!$isValidLoaiStt){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        $isExistParam = $this->checkExistParam($input['phongId'], $input['benhVienId']);
        $isMaSoKiosk = (isset($input['maSoKiosk']) && $input['maSoKiosk'] > 0 ) ? true : false;
        
        //co tham so $phongId, $benhVienId, $maSoKiosk
        if($isExistParam && $isMaSoKiosk) {
            $data = $this->service->getSttDonTiep($input);
            return $this->respond($data);
        } else {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
    }
    
    public function goiSttDonTiep(Request $request)
    {
        $input = $request->all();
        $isValidLoaiStt = in_array($input['loaiStt'], self::LOAI_STT);
        $isExistParam = $this->checkExistParam($input['phongId'], $input['benhVienId']);
        $isQuaySo = (isset($input['quaySo']) && $input['quaySo'] > 0 ) ? true : false;
        $authUsersId = $input['authUsersId'];
        
        //sai tham so $loaiStt
        if(!$isValidLoaiStt){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        //khong co tham so $phongId, $benhVienId, $quaySo
        if(!$isExistParam || !$isQuaySo) {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        //khong ton tai $authUsersId
        $bool = $this->authService->getUserById($authUsersId);
        if(!$bool){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        $data = $this->service->goiSttDonTiep($input);
        
        //ko co du lieu stt
        if($data === null)
            $this->setStatusCode(404);
        
        return $this->respond($data);
    }
    
    public function loadSttDonTiep(Request $request)
    {
        $isExistParam = $this->checkExistParam($request['phongId'], $request['benhVienId']);
        $input = $request->all();
        
        if($isExistParam) {
            $data = $this->service->loadSttDonTiep($input);
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
        $isExistParam = $this->checkExistParam($request['phongId'], $request['benhVienId']);
        $input = $request->all();
        
        if($isExistParam) {
            $data = $this->service->countSttDonTiep($input);
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