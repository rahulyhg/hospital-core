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
            'hosobenhan.soluutru',
            'hosobenhan.sovaovien',
            'vienphi.vienphicode',
            'departmentgroup.departmentgroupname',
            'department.departmentname',
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
            'tt2.diengiai as doituongbenhnhan',
            'bhyt.bhytcode',
            'bhyt.bhyt_loaiid',
            'bhyt.bhytfromdate',
            'bhyt.bhytutildate',
            'bhyt.macskcbbd',
            'bhyt.noisinhsong',
            'bhyt.du5nam6thangluongcoban',
            'medicalrecord.medicalrecordcode',
            'tt1.diengiai as loaibenhan_name',
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
                ->leftJoin('red_trangthai as tt1', function($join) {
                    $join->on('tt1.giatri', '=', 'medicalrecord.loaibenhanid')
                        ->where('tt1.tablename', '=', 'loaibenhanid');
                })
                ->leftJoin('red_trangthai as tt2', function($join) {
                    $join->on('tt2.giatri', '=', 'medicalrecord.doituongbenhnhanid')
                        ->where('tt2.tablename', '=', 'doituongbenhnhan');
                })
                ->leftJoin('departmentgroup', 'departmentgroup.departmentgroupid', '=', 'medicalrecord.departmentgroupid')
                ->leftJoin('department', 'department.departmentid', '=', 'medicalrecord.departmentid')
                ->join('bhyt', 'bhyt.bhytid', '=', 'medicalrecord.bhytid')
                ->join('vienphi', 'vienphi.hosobenhanid', '=', 'hosobenhan.hosobenhanid')
                ->where($where)
                ->get($column);
          
        $array = json_decode($data, true);
        
        return collect($array)->first();
    }
  
    public function CreateDataHosobenhan(array $input)
    {
        $id = Hosobenhan::create($input)->hosobenhanid;
        return $id;
    }
}