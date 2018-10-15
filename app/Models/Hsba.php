<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hsba extends Model
{
    protected $table = 'hsba';

    protected $primaryKey = 'id';

    //protected $fillable = ['soluutru', 'sovaovien', 'soluutru_remark', 'soluutru_vitri', 'soluutru_nguoiluu', 'hosobenhancode', 'isuploaded', 'isdownloaded', 'loaibenhanid', 'userid', 'departmentgroupid', 'departmentid', 'hinhthucvaovienid', 'ketquadieutriid', 'xutrikhambenhid', 'hinhthucravienid', 'hosobenhanstatus', 'patientid', 'hosobenhandate', 'hosobenhandate_ravien', 'chandoanvaovien_code', 'chandoanvaovien', 'chandoanravien_code', 'chandoanravien', 'chandoanravien_kemtheo_code', 'chandoanravien_kemtheo', 'lastaccessdate', 'hosobenhanremark', 'patientname', 'birthday', 'birthday_year', 'gioitinhcode', 'nghenghiepcode', 'hc_dantoccode', 'hc_quocgiacode', 'hc_sonha', 'hc_thon', 'hc_xacode', 'hc_huyencode', 'hc_tinhcode', 'noilamviec', 'nguoithan', 'nguoithan_name', 'nguoithan_phone', 'nguoithan_address', 'gioitinhname', 'nghenghiepname', 'hc_dantocname', 'hc_quocgianame', 'hc_xaname', 'hc_huyenname', 'hc_tinhname', 'version', 'sync_flag', 'update_flag', 'patient_id', 'imagedata', 'imagesize', 'patientcode', 'isencript', 'soluutru_thoigianluu', 'ismocapcuu', 'bhytcode', 'malifegap'];

    protected $guarded = ['id'];

    public $timestamps = false;

}
