<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $hosobenhanid
 * @property string $soluutru
 * @property string $sovaovien
 * @property string $soluutru_remark
 * @property string $soluutru_vitri
 * @property int $soluutru_nguoiluu
 * @property string $hosobenhancode
 * @property int $isuploaded
 * @property int $isdownloaded
 * @property int $loaibenhanid
 * @property int $userid
 * @property int $departmentgroupid
 * @property int $departmentid
 * @property int $hinhthucvaovienid
 * @property int $ketquadieutriid
 * @property int $xutrikhambenhid
 * @property int $hinhthucravienid
 * @property int $hosobenhanstatus
 * @property int $patientid
 * @property string $hosobenhandate
 * @property string $hosobenhandate_ravien
 * @property string $chandoanvaovien_code
 * @property string $chandoanvaovien
 * @property string $chandoanravien_code
 * @property string $chandoanravien
 * @property string $chandoanravien_kemtheo_code
 * @property string $chandoanravien_kemtheo
 * @property string $lastaccessdate
 * @property string $hosobenhanremark
 * @property string $patientname
 * @property string $birthday
 * @property int $birthday_year
 * @property string $gioitinhcode
 * @property string $nghenghiepcode
 * @property string $hc_dantoccode
 * @property string $hc_quocgiacode
 * @property string $hc_sonha
 * @property string $hc_thon
 * @property string $hc_xacode
 * @property string $hc_huyencode
 * @property string $hc_tinhcode
 * @property string $noilamviec
 * @property string $nguoithan
 * @property string $nguoithan_name
 * @property string $nguoithan_phone
 * @property string $nguoithan_address
 * @property string $gioitinhname
 * @property string $nghenghiepname
 * @property string $hc_dantocname
 * @property string $hc_quocgianame
 * @property string $hc_xaname
 * @property string $hc_huyenname
 * @property string $hc_tinhname
 * @property string $version
 * @property int $sync_flag
 * @property int $update_flag
 * @property string $patient_id
 * @property string $imagedata
 * @property int $imagesize
 * @property string $patientcode
 * @property int $isencript
 * @property string $soluutru_thoigianluu
 * @property int $ismocapcuu
 * @property string $bhytcode
 * @property string $malifegap
 */
class Hosobenhan extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'hosobenhan';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'hosobenhanid';

    /**
     * @var array
     */
    //protected $fillable = ['soluutru', 'sovaovien', 'soluutru_remark', 'soluutru_vitri', 'soluutru_nguoiluu', 'hosobenhancode', 'isuploaded', 'isdownloaded', 'loaibenhanid', 'userid', 'departmentgroupid', 'departmentid', 'hinhthucvaovienid', 'ketquadieutriid', 'xutrikhambenhid', 'hinhthucravienid', 'hosobenhanstatus', 'patientid', 'hosobenhandate', 'hosobenhandate_ravien', 'chandoanvaovien_code', 'chandoanvaovien', 'chandoanravien_code', 'chandoanravien', 'chandoanravien_kemtheo_code', 'chandoanravien_kemtheo', 'lastaccessdate', 'hosobenhanremark', 'patientname', 'birthday', 'birthday_year', 'gioitinhcode', 'nghenghiepcode', 'hc_dantoccode', 'hc_quocgiacode', 'hc_sonha', 'hc_thon', 'hc_xacode', 'hc_huyencode', 'hc_tinhcode', 'noilamviec', 'nguoithan', 'nguoithan_name', 'nguoithan_phone', 'nguoithan_address', 'gioitinhname', 'nghenghiepname', 'hc_dantocname', 'hc_quocgianame', 'hc_xaname', 'hc_huyenname', 'hc_tinhname', 'version', 'sync_flag', 'update_flag', 'patient_id', 'imagedata', 'imagesize', 'patientcode', 'isencript', 'soluutru_thoigianluu', 'ismocapcuu', 'bhytcode', 'malifegap'];

    protected $guarded = ['hosobenhanid'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

}
