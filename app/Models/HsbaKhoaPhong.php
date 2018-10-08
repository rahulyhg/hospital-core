<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $medicalrecordid
 * @property string $medicalrecordcode
 * @property int $sothutuid
 * @property int $sothutunumber
 * @property int $sothutuphongkhamid
 * @property int $sothutuphongkhamnumber
 * @property int $vienphiid
 * @property int $hosobenhanid
 * @property int $medicalrecordid_next
 * @property int $medicalrecordid_master
 * @property int $medicalrecordstatus
 * @property int $departmentgroupid
 * @property int $departmentid
 * @property string $giuong
 * @property int $loaibenhanid
 * @property int $userid
 * @property int $patientid
 * @property int $doituongbenhnhanid
 * @property int $bhytid
 * @property string $lydodenkham
 * @property string $yeucaukham
 * @property string $thoigianvaovien
 * @property string $chandoanvaovien
 * @property string $chandoantuyenduoi
 * @property string $chandoantuyenduoi_code
 * @property string $noigioithieucode
 * @property string $chandoanvaovien_code
 * @property string $chandoanvaovien_kemtheo
 * @property string $chandoanvaovien_kemtheo_code
 * @property string $chandoankkb
 * @property string $chandoankkb_code
 * @property string $chandoanvaokhoa
 * @property string $chandoanvaokhoa_code
 * @property string $chandoanvaokhoa_kemtheo
 * @property string $chandoanvaokhoa_kemtheo_code
 * @property int $isthuthuat
 * @property int $isphauthuat
 * @property int $hinhthucvaovienid
 * @property int $backdepartmentid
 * @property int $uutienkhamid
 * @property int $noigioithieuid
 * @property int $vaoviencungbenhlanthu
 * @property string $thoigianravien
 * @property string $chandoanravien
 * @property string $chandoanravien_code
 * @property string $chandoanravien_kemtheo
 * @property string $chandoanravien_kemtheo_code
 * @property string $chandoanravien_kemtheo1
 * @property string $chandoanravien_kemtheo_code1
 * @property string $chandoanravien_kemtheo2
 * @property string $chandoanravien_kemtheo_code2
 * @property int $xutrikhambenhid
 * @property int $hinhthucravienid
 * @property int $ketquadieutriid
 * @property int $nextdepartmentid
 * @property int $nexthospitalid
 * @property int $istaibien
 * @property int $isbienchung
 * @property int $giaiphaubenhid
 * @property string $lydovaovien
 * @property int $vaongaythucuabenh
 * @property string $quatrinhbenhly
 * @property string $tiensubenh_banthan
 * @property string $tiensubenh_giadinh
 * @property string $khambenh_toanthan
 * @property string $khambenh_mach
 * @property string $khambenh_nhietdo
 * @property string $khambenh_huyetap_low
 * @property string $khambenh_huyetap_high
 * @property string $khambenh_nhiptho
 * @property string $khambenh_cannang
 * @property string $khambenh_chieucao
 * @property string $khambenh_vongnguc
 * @property string $khambenh_vongdau
 * @property string $khambenh_bophan
 * @property string $tomtatkqcanlamsang
 * @property string $chandoanbandau
 * @property string $daxuly
 * @property string $tomtatbenhan
 * @property string $chandoankhoakhambenh
 * @property string $daxulyotuyenduoi
 * @property string $medicalrecordremark
 * @property string $lastaccessdate
 * @property int $canlamsangstatus
 * @property string $version
 * @property int $sync_flag
 * @property int $update_flag
 * @property int $lastuserupdated
 * @property string $lasttimeupdated
 * @property int $keylock
 * @property int $cv_chuyenvien_hinhthucid
 * @property int $cv_chuyenvien_lydoid
 * @property int $cv_chuyendungtuyen
 * @property int $cv_chuyenvuottuyen
 * @property string $xetnghiemcanthuchienlai
 * @property string $loidanbacsi
 * @property int $nextbedrefid
 * @property string $nextbedrefid_nguoinha
 * @property string $chandoanbandau_code
 * @property string $thoigianchuyenden
 * @property string $khambenh_thilucmatphai
 * @property string $khambenh_thilucmattrai
 * @property string $khambenh_klthilucmatphai
 * @property string $khambenh_klthilucmattrai
 * @property string $khambenh_nhanapmatphai
 * @property string $khambenh_nhanapmattrai
 */
class HsbaKhoaPhong extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'hsba_khoa_phong';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    //protected $fillable = ['medicalrecordcode', 'sothutuid', 'sothutunumber', 'sothutuphongkhamid', 'sothutuphongkhamnumber', 'vienphiid', 'hosobenhanid', 'medicalrecordid_next', 'medicalrecordid_master', 'medicalrecordstatus', 'departmentgroupid', 'departmentid', 'giuong', 'loaibenhanid', 'userid', 'patientid', 'doituongbenhnhanid', 'bhytid', 'lydodenkham', 'yeucaukham', 'thoigianvaovien', 'chandoanvaovien', 'chandoantuyenduoi', 'chandoantuyenduoi_code', 'noigioithieucode', 'chandoanvaovien_code', 'chandoanvaovien_kemtheo', 'chandoanvaovien_kemtheo_code', 'chandoankkb', 'chandoankkb_code', 'chandoanvaokhoa', 'chandoanvaokhoa_code', 'chandoanvaokhoa_kemtheo', 'chandoanvaokhoa_kemtheo_code', 'isthuthuat', 'isphauthuat', 'hinhthucvaovienid', 'backdepartmentid', 'uutienkhamid', 'noigioithieuid', 'vaoviencungbenhlanthu', 'thoigianravien', 'chandoanravien', 'chandoanravien_code', 'chandoanravien_kemtheo', 'chandoanravien_kemtheo_code', 'chandoanravien_kemtheo1', 'chandoanravien_kemtheo_code1', 'chandoanravien_kemtheo2', 'chandoanravien_kemtheo_code2', 'xutrikhambenhid', 'hinhthucravienid', 'ketquadieutriid', 'nextdepartmentid', 'nexthospitalid', 'istaibien', 'isbienchung', 'giaiphaubenhid', 'lydovaovien', 'vaongaythucuabenh', 'quatrinhbenhly', 'tiensubenh_banthan', 'tiensubenh_giadinh', 'khambenh_toanthan', 'khambenh_mach', 'khambenh_nhietdo', 'khambenh_huyetap_low', 'khambenh_huyetap_high', 'khambenh_nhiptho', 'khambenh_cannang', 'khambenh_chieucao', 'khambenh_vongnguc', 'khambenh_vongdau', 'khambenh_bophan', 'tomtatkqcanlamsang', 'chandoanbandau', 'daxuly', 'tomtatbenhan', 'chandoankhoakhambenh', 'daxulyotuyenduoi', 'medicalrecordremark', 'lastaccessdate', 'canlamsangstatus', 'version', 'sync_flag', 'update_flag', 'lastuserupdated', 'lasttimeupdated', 'keylock', 'cv_chuyenvien_hinhthucid', 'cv_chuyenvien_lydoid', 'cv_chuyendungtuyen', 'cv_chuyenvuottuyen', 'xetnghiemcanthuchienlai', 'loidanbacsi', 'nextbedrefid', 'nextbedrefid_nguoinha', 'chandoanbandau_code', 'thoigianchuyenden', 'khambenh_thilucmatphai', 'khambenh_thilucmattrai', 'khambenh_klthilucmatphai', 'khambenh_klthilucmattrai', 'khambenh_nhanapmatphai', 'khambenh_nhanapmattrai'];

    protected $guarded = ['id'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

}
