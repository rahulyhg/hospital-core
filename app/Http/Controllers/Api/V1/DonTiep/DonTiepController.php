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
     /**
     * __construct.
     *
     * @param $service
     */
    public function __construct(SttDonTiepService $SttDonTiepService, HsbaKhoaPhongService $HsbaKhoaPhongService, HsbaService $HsbaService, BenhNhanService $BenhNhanService)
    {
        $this->SttDonTiepService = $SttDonTiepService;
        $this->HsbaKhoaPhongService = $HsbaKhoaPhongService;
        $this->HsbaService = $HsbaService;
        $this->BenhNhanService = $BenhNhanService;
    }
    
    public function getInfoPatientByStt($stt, $phong_id, $benh_vien_id)
    {
        $data = $this->SttDonTiepService->getInfoPatientByStt($stt, $phong_id, $benh_vien_id);
        
        return $data;
    }
    
    public function getListPatientByKhoaPhong($type = 'HC', $phong_id = 0, Request $request)
    {
        $start_day = $request->query('start_day', Carbon::today());
        $end_day = $request->query('end_day', Carbon::today());
        $offset = $request->query('offset', 0);
        $limit = $request->query('limit', 20);
        $keyword = $request->query('keyword', '');
        
        //$redis = Redis::connection();
        
        if($type == "HC"){
            
            //$data = $redis->get('list_BN_HC');
            
            //if($data) {
                //$list_BN = $data;
            //} else {
                $list_BN = $this->HsbaKhoaPhongService->getListBN_HC($start_day, $end_day, $offset, $limit, $keyword);
                //$redis->set('list_BN_HC', $list_BN);
            //}
        } else {
            //$data = $redis->get('list_BN_PK');
            
            //if($data) {
                //$list_BN = $data;
            //} else {
                $list_BN = $this->HsbaKhoaPhongService->getListBN_PK($phong_id, $start_day, $end_day, $offset, $limit, $keyword);
            //}
        }
        
        return $list_BN;
    }
    
    public function getHsbaByHsbaId($hsba_id, $phong_id){
        $data = $this->HsbaService->getHsbaByHsbaId($hsba_id, $phong_id);
        return $data;
    }
    
    public function register(Request $request)
    {
        $data = $this->BenhNhanService->createBenhNhan($request);
        return $data;
    }
}
