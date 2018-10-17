<?php
namespace App\Http\Controllers\Api\V1\DonTiep;

use Illuminate\Http\Request;
use App\Services\SttDonTiepService;
use App\Services\HsbaKhoaPhongService;
use App\Services\HsbaService;
use App\Services\BenhNhanService;
use App\Http\Controllers\Api\V1\APIController;
use Carbon\Carbon;
//use Illuminate\Support\Facades\Redis;

class DonTiepController extends APIController
{
    public function __construct(SttDonTiepService $sttDonTiepService, HsbaKhoaPhongService $hsbaKhoaPhongService, HsbaService $hsbaService, BenhNhanService $benhNhanService)
    {
        $this->sttDonTiepService = $sttDonTiepService;
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
        $this->hsbaService = $hsbaService;
        $this->benhNhanService = $benhNhanService;
    }
    
    public function getInfoPatientByStt($stt, $phongId, $benh_vien_id)
    {
        $data = $this->sttDonTiepService->getInfoPatientByStt($stt, $phongId, $benh_vien_id);
        
        return $data;
    }
    
    public function getListPatientByKhoaPhong($type = 'HC', $phongId = 0, Request $request)
    {
        $startDay = $request->query('start_day', Carbon::today());
        $endDay = $request->query('end_day', Carbon::today());
        $offset = $request->query('offset', 0);
        $limit = $request->query('limit', 20);
        $keyword = $request->query('keyword', '');
        
        //$redis = Redis::connection();
        
        if($type == "HC"){
            
            //$data = $redis->get('list_BN_HC');
            
            //if($data) {
                //$list_BN = $data;
            //} else {
                $list_BN = $this->hsbaKhoaPhongService->getListBN_HC($startDay, $endDay, $offset, $limit, $keyword);
                //$redis->set('list_BN_HC', $list_BN);
            //}
        } else {
            //$data = $redis->get('list_BN_PK');
            
            //if($data) {
                //$list_BN = $data;
            //} else {
                $list_BN = $this->hsbaKhoaPhongService->getListBN_PK($phongId, $startDay, $endDay, $offset, $limit, $keyword);
            //}
        }
        
        return $list_BN;
    }
    
    public function getHsbaByHsbaId($hsbaId, $phongId){
        $data = $this->hsbaService->getHsbaByHsbaId($hsbaId, $phongId);
        return $data;
    }
    
    public function register(Request $request)
    {
        $data = $this->benhNhanService->createBenhNhan($request);
        return $data;
    }
}
