<?php
namespace App\Http\Controllers\Api\V1\DonTiep;

use Illuminate\Http\Request;
use App\Services\SttDonTiepService;
use App\Http\Controllers\Api\V1\APIController;

class SttDonTiepController extends APIController
{
    public function __construct(SttDonTiepService $sttDonTiepService)
    {
        $this->service = $sttDonTiepService;
    }
    
    public function makeSttDonTiepWhenScanCard(Request $request)
    {
        $data = $this->service->makeSttDonTiepWhenScanCard($request);
        
        return $data;
    }
    
    public function scanCard(Request $request)
    {
        $data = $this->service->scanCard($request->cardCode);
        
        return $data;
    }
    
    public function getSttDonTiep(Request $request)
    {
        $data = $this->service->getSttDonTiep($request);
        
        return $data;
    }
    
    public function goiSttDonTiep(Request $request)
    {
        $data = $this->service->goiSttDonTiep($request);
        
        return $data;
    }
    
    public function loadSttDonTiep(Request $request)
    {
        $data = $this->service->loadSttDonTiep($request);
        
        return $data;
    }
    
    public function finishSttDonTiep($sttId)
    {
        $this->service->finishSttDonTiep($sttId);
    }
    
    public function countSttDonTiep(Request $request)
    {
        $data = $this->service->countSttDonTiep($request);
        
        return $data;
    }
}