<?php

namespace App\Http\Controllers\Api\V1\DonTiep;

use Illuminate\Http\Request;
use App\Services\RedSttDontiepService;
use App\Services\MedicalRecordService;
use App\Services\HosobenhanService;
use App\Http\Controllers\Api\V1\APIController;
use Carbon\Carbon;

class DontiepController extends APIController
{
     /**
     * __construct.
     *
     * @param $service
     */
    public function __construct(RedSttDontiepService $sttdontiepservice, MedicalRecordService $medicalrecordservice, HosobenhanService $hosobenhanservice)
    {
        $this->sttdontiepservice = $sttdontiepservice;
        $this->medicalrecordservice = $medicalrecordservice;
        $this->hosobenhanservice = $hosobenhanservice;
    }
    
    public function getInfoPatientByStt($stt, $id_phong, $id_benh_vien)
    {
        $data = $this->sttdontiepservice->getInfoPatientByStt($stt, $id_phong, $id_benh_vien);
        
        return $data;
    }
    
    public function getListPatientByKhoaPhong($type = 'HC', $departmentid = 0, Request $request)
    {
        $start_day = $request->query('start_day', Carbon::today());
        $end_day = $request->query('end_day', Carbon::today());
        $offset = $request->query('offset', 0);
        $limit = $request->query('limit', 10);
        
        if($type == "HC"){
            $list_BN = $this->medicalrecordservice->getListBN_HC($start_day, $end_day, $offset, $limit);
        } else {
            $list_BN = $this->medicalrecordservice->getListBN_PK($departmentid, $start_day, $end_day, $offset, $limit);
        }
        
        return $list_BN;
    }
    
    public function getHSBAByHosobenhanID($hosobenhanid){
        $data = $this->hosobenhanservice->getHSBAByHosobenhanID($hosobenhanid);
        
        return $data;
    }
}
