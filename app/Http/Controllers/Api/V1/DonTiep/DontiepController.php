<?php

namespace App\Http\Controllers\Api\V1\DonTiep;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\RedSttDontiepService;
use App\Services\MedicalRecordService;
use Carbon\Carbon;

class DontiepController extends Controller
{
     /**
     * __construct.
     *
     * @param $service
     */
    public function __construct(RedSttDontiepService $sttdontiepservice, MedicalRecordService $medicalrecordservice)
    {
        $this->sttdontiepservice = $sttdontiepservice;
        $this->medicalrecordservice = $medicalrecordservice;
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
    
    public function getInfoPatientByPatientID($patientid){
        $data = $this->medicalrecordservice->getInfoPatientByPatientID($patientid);
        
        return $data;
    }
}
