<?php
namespace App\Http\Controllers\Api\V1\DonTiep;

use Illuminate\Http\Request;
use App\Services\SttDonTiepService;
use App\Http\Controllers\Api\V1\APIController;

class SttDonTiepController extends APIController
{
    public function __construct(SttDonTiepService $SttDonTiepService)
    {
        $this->service = $SttDonTiepService;
    }
    
    public function checkCardCode(Request $request)
    {
        $data = $this->service->getInfoPatientByCard($request);
        
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
}