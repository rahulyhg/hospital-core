<?php
namespace App\Http\Controllers\Api\V1\DonTiep;

use Illuminate\Http\Request;
use App\Http\Requests\DangKyKhamBenhFormRequest;
use App\Http\Requests\UpdateHsbaFormRequest;
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
    
    public function getListPatientByKhoaPhong($type = 'HC', $phongId = 0, $benhVienId, Request $request)
    {
        $startDay = $request->query('startDay', Carbon::today());
        $endDay = $request->query('endDay', Carbon::today());
        $limit = $request->query('limit', 20);
        $page = $request->query('page', 1);
        $keyword = $request->query('keyword', '');
        
        //$redis = Redis::connection();
        
        if(!in_array($type, ['HC', 'PK']) || $phongId === null || $benhVienId === null){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        if($type == "HC"){
            
            //$data = $redis->get('list_BN_HC');
            
            //if($data) {
                //$listBenhNhan = $data;
            //} else {
                $listBenhNhan = $this->hsbaKhoaPhongService->getListBenhNhanHanhChanh($benhVienId, $startDay, $endDay, $limit, $page, $keyword);
                //$redis->set('list_BN_HC', $listBenhNhan);
            //}
        } else {
            //$data = $redis->get('list_BN_PK');
            
            //if($data) {
                //$listBenhNhan = $data;
            //} else {
                $listBenhNhan = $this->hsbaKhoaPhongService->getListBenhNhanPhongKham($phongId, $benhVienId, $startDay, $endDay, $limit, $page, $keyword);
            //}
        }
        
        return $this->respond($listBenhNhan);
    }
    
    public function getHsbaByHsbaId($hsbaId, $phongId) 
    {
        if(is_numeric($hsbaId) && is_numeric($phongId)) {
            $data = $this->hsbaService->getHsbaByHsbaId($hsbaId, $phongId);
            return $this->respond($data);
        } else {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
    }
    
    public function updateHsba($hsbaId, UpdateHsbaFormRequest $request)
    {
        try {
            if(is_numeric($hsbaId)) {
                $this->hsbaService->updateHsba($hsbaId, $request);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
  
    public function register(DangKyKhamBenhFormRequest $request)
    {   
        try 
        {
            $id = $this->benhNhanService->createBenhNhan($request);
            $this->setStatusCode(201);
            return $this->respond([]);
            
            // return $this->respondCreatedWithData(
            //     [
            //         'id'=>$id
            //     ]
            // );
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
            return $ex;
        }

    }
}
