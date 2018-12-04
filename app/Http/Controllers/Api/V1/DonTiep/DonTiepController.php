<?php
namespace App\Http\Controllers\Api\V1\DonTiep;

use Illuminate\Http\Request;
use App\Http\Requests\DangKyKhamBenhFormRequest;
use App\Http\Requests\UpdateHsbaFormRequest;
use App\Services\SttDonTiepService;
use App\Services\HsbaKhoaPhongService;
use App\Services\HsbaService;
use App\Services\BenhNhanServiceV2;
use App\Services\BhytService;
use App\Services\PhongService;
use App\Http\Controllers\Api\V1\APIController;
use Carbon\Carbon;
//use Illuminate\Support\Facades\Redis;

class DonTiepController extends APIController
{
    public function __construct(
        SttDonTiepService $sttDonTiepService, 
        HsbaKhoaPhongService $hsbaKhoaPhongService, 
        HsbaService $hsbaService, 
        BenhNhanServiceV2 $benhNhanService, 
        BhytService $bhytService,
        PhongService $phongService
    )
    {
        $this->sttDonTiepService = $sttDonTiepService;
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
        $this->hsbaService = $hsbaService;
        $this->benhNhanService = $benhNhanService;
        $this->bhytService = $bhytService;
        $this->phongService = $phongService;
    }
    
    public function getListPatientByKhoaPhong($phongId = 0, $benhVienId, Request $request)
    {
        $startDay = $request->query('startDay', Carbon::today());
        $endDay = $request->query('endDay', Carbon::today());
        $limit = $request->query('limit', 20);
        $page = $request->query('page', 1);
        $keyword = $request->query('keyword', '');
        $status = $request->query('status', 0);
        
        //$redis = Redis::connection();
        
        if($phongId === null || $benhVienId === null){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        try 
        {
            $dataBenhVienThietLap = $this->hsbaKhoaPhongService->getBenhVienThietLap($benhVienId);
            $listBenhNhan = $this->hsbaKhoaPhongService->getList($phongId, $benhVienId, $dataBenhVienThietLap, $startDay, $endDay, $limit, $page, $keyword, $status);
            $this->setStatusCode(200);
            return $this->respond($listBenhNhan);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
        
        // if(empty($listBenhNhan)) {
        //     $this->setStatusCode(400);
        //     return $this->respond([]);
        // }
        //if($type == "HC"){
            //$data = $redis->get('list_BN_HC');
            
            //if($data) {
                //$listBenhNhan = $data;
            //} else {
                //$listBenhNhan = $this->hsbaKhoaPhongService->getListBenhNhanHanhChanh($benhVienId, $startDay, $endDay, $limit, $page, $keyword);
                //$redis->set('list_BN_HC', $listBenhNhan);
            //}
        //} else {
            //$data = $redis->get('list_BN_PK');
            
            //if($data) {
                //$listBenhNhan = $data;
            //} else {
                //$listBenhNhan = $this->hsbaKhoaPhongService->getListBenhNhanPhongKham($phongId, $benhVienId, $startDay, $endDay, $limit, $page, $keyword);
            //}
        //}
        
        return $this->respond($listBenhNhan);
    }
    
    public function getByHsbaId($hsbaId) 
    {
        if(is_numeric($hsbaId)) {
            $data = $this->hsbaKhoaPhongService->getByHsbaId($hsbaId);
            return $this->respond($data);
        } else {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
    }
    
    public function updateInfoPatient($hsbaId, UpdateHsbaFormRequest $request)
    {
        try {
            if(is_numeric($hsbaId)) {
                $input = $request->except('location');
                $this->hsbaService->updateHsba($hsbaId, $input);

            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }
  
    public function register(DangKyKhamBenhFormRequest $request)
    {   
        try 
        {
            $dataPrint = $this->benhNhanService->registerBenhNhan($request);
            $this->setStatusCode(201);
            return $this->respond($dataPrint);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }
}
