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
use App\Services\VienPhiService;
use App\Http\Controllers\Api\V1\APIController;
use Carbon\Carbon;

class DonTiepController extends APIController
{
    public function __construct(
        SttDonTiepService $sttDonTiepService, 
        HsbaKhoaPhongService $hsbaKhoaPhongService, 
        HsbaService $hsbaService, 
        BenhNhanServiceV2 $benhNhanService, 
        BhytService $bhytService,
        PhongService $phongService,
        VienPhiService $vienPhiService
    )
    {
        $this->sttDonTiepService = $sttDonTiepService;
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
        $this->hsbaService = $hsbaService;
        $this->benhNhanService = $benhNhanService;
        $this->bhytService = $bhytService;
        $this->phongService = $phongService;
        $this->vienPhiService = $vienPhiService;
    }
    
    public function getListPatientByKhoaPhong($phongId = 0, $benhVienId, Request $request)
    {
        $startDay = $request->query('startDay', Carbon::today());
        $endDay = $request->query('endDay', Carbon::today());
        $limit = $request->query('limit', 20);
        $page = $request->query('page', 1);
        $keyword = $request->query('keyword', '');
        $status = $request->query('status', 0);
        $typeDay = $request->query('typeDay', 0);
        $loaiVienPhi = $request->query('loaiVienPhi', '');
        
        $option = [
            'typeDay'           => $typeDay,
            'loaiVienPhi'       => $loaiVienPhi
        ];
        
        if($phongId === null || $benhVienId === null){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        try 
        {
            $dataBenhVienThietLap = $this->hsbaKhoaPhongService->getBenhVienThietLap($benhVienId);
            $listBenhNhan = $this->hsbaKhoaPhongService->getList($phongId, $benhVienId, $dataBenhVienThietLap, $startDay, $endDay, $limit, $page, $keyword, $status, $option);
            $this->setStatusCode(200);
            return $this->respond($listBenhNhan);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
        
        return $this->respond($listBenhNhan);
    }
    
    public function getByHsbaId($hsbaId) 
    {
        if(is_numeric($hsbaId)) {
            $data = $this->hsbaKhoaPhongService->getByHsbaId($hsbaId);
            if($data['ms_bhyt']) {
                $input['ms_bhyt'] = $data['ms_bhyt'];
                $input['vien_phi_id'] = $data['vien_phi_id'];
                $input['loai_vien_phi'] = $data['loai_vien_phi'];
                //cần check thời hạn thẻ BHYT
                $data['muc_huong'] = $this->vienPhiService->getMucHuong($input);
            } else {
                $data['muc_huong'] = 0;
            }
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
                $input = $request->except('location','tinh_key','huyen_key','xa_key','thx_name','thx_key');
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
    
    public function pushToRedisFromQueue(Request $request) {
        try 
        {
            /*
           {
            	"benh_vien_id": 1,
            	"hsba_khoa_phong_id": 10004,
            	"ten_benh_nhan": "NVF",
            	"thoi_gian_vao_vien": "2018-11-18 15:29:26",
            	"thoi_gian_ra_vien": "",
            	"trang_thai_cls": 1,
            	"ten_trang_thai_cls": "TEN_CLS",
            	"trang_thai": 1,
            	"ten_trang_thai": "TRỐN TRẠI"
            }
            */
            $params = $request->only('message_id','message_attributes','message_body');
            $messageAttributes = json_decode($params['message_attributes'],true);
            $messageBody = json_decode($params['message_body'],true);
            //var_dump($messageBody);die;
            
            $result = $this->hsbaKhoaPhongService->addToCache($params['message_id'],$messageAttributes,$messageBody);
            $this->setStatusCode(201);
            return $this->respond(true);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        }    
        
    }
}
