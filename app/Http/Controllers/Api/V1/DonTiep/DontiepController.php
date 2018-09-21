<?php

namespace App\Http\Controllers\Api\V1\DonTiep;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\RedSttDontiepService;
use App\Services\MedicalRecordService;

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
    
    public function getListPatientByKhoaPhong($type = 'HC', $departmentid = 0, $start_day, $end_day)
    {
        if($type == "HC"){
            $list_BN = $this->medicalrecordservice->getListBN_HC($start_day, $end_day);
        } else {
            $list_BN = $this->medicalrecordservice->getListBN_PK($departmentid, $start_day, $end_day);
        }
        
        return $list_BN;
    }
}
