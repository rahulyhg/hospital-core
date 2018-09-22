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
    
    public function getHSBAByHosobenhanID($hosobenhanid)
    {
        $loaibenhanid = 24; //kham benh
        
        $where = [
            ['medicalrecord.loaibenhanid', '=', $loaibenhanid],
            ['hosobenhan.hosobenhanid', '=', $hosobenhanid],
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
            'hosobenhan.hc_tinhname'
        ];
        
        $data = DB::table('hosobenhan')
                ->join('medicalrecord', 'medicalrecord.hosobenhanid', '=', 'hosobenhan.hosobenhanid')
                ->where($where)
                ->get($column);
        
        return $data;
    }
}