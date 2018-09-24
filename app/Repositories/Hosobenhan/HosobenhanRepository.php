<?php
namespace App\Repositories\Hosobenhan;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Hosobenhan;

class HosobenhanRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return Hosobenhan::class;
    }
    
    public function getHosobenhanByPatientID($patientid)
    {
        $result = $this->model->where('patientid', $patientid)->first();
        
        return $result;
    }
    
    public function getHSBAByHosobenhanID($hosobenhanid, $departmentid)
    {
        $loaibenhanid = 24; //kham benh
        
        if($departmentid != 0)
            $where = [
                ['medicalrecord.departmentid', '=', $departmentid],
                ['hosobenhan.hosobenhanid', '=', $hosobenhanid]
            ];
        else
            $where = [
                ['medicalrecord.loaibenhanid', '=', $loaibenhanid],
                ['hosobenhan.hosobenhanid', '=', $hosobenhanid]
            ];
        
        $column = [
            'hosobenhan.hosobenhanid',
            'hosobenhan.patientid',
            'hosobenhan.patientcode',
            'hosobenhan.patientname',
            'hosobenhan.birthday',
            'hosobenhan.birthday_year',
            'hosobenhan.gioitinhname',
            'hosobenhan.nghenghiepname',
            'hosobenhan.hc_quocgianame',
            'hosobenhan.hc_dantocname',
            'hosobenhan.bhytcode',
            'hosobenhan.noilamviec',
            'hosobenhan.hc_sonha',
            'hosobenhan.hc_thon',
            'hosobenhan.hc_xaname',
            'hosobenhan.hc_huyenname',
            'hosobenhan.hc_tinhname',
            'hosobenhan.imagedata',
            'hosobenhan.imagesize',
            'hosobenhan.nguoithan',
            'hosobenhan.nguoithan_name',
            'hosobenhan.nguoithan_phone',
            'hosobenhan.nguoithan_address',
            'bhyt.bhytcode',
            'bhyt.bhyt_loaiid',
            'bhyt.bhytfromdate',
            'bhyt.bhytutildate',
            'bhyt.macskcbbd',
            'bhyt.noisinhsong',
            'bhyt.du5nam6thangluongcoban',
            'medicalrecord.medicalrecordcode',
            'medicalrecord.chandoantuyenduoi',
            'medicalrecord.chandoantuyenduoi_code',
            'medicalrecord.noigioithieucode',
            'medicalrecord.departmentid',
            'medicalrecord.thoigianvaovien',
            'medicalrecord.noigioithieuid',
            'medicalrecord.chandoanvaovien',
            'medicalrecord.hinhthucvaovienid',
            'medicalrecord.thoigianravien',
            'medicalrecord.chandoanravien_code',
            'medicalrecord.chandoanravien',
            'medicalrecord.chandoanravien_kemtheo_code',
            'medicalrecord.chandoanravien_kemtheo',
            'medicalrecord.ketquadieutriid',
            'medicalrecord.hinhthucravienid'
        ];
        
        $data = DB::table('hosobenhan')
                ->join('medicalrecord', 'medicalrecord.hosobenhanid', '=', 'hosobenhan.hosobenhanid')
                ->join('bhyt', 'bhyt.bhytid', '=', 'medicalrecord.bhytid')
                ->where($where)
                ->get($column);
          
        $array = json_decode($data, true);
        
        return collect($array)->first();
    }
}