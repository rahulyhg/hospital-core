<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HsbaDonVi extends Model
{
    protected $table = 'hsba_don_vi';

    protected $primaryKey = 'id';

    //protected $fillable = ['medicalrecordcode', 'sothutuid', 'sothutunumber', 'sothutuphongkhamid', 'sothutuphongkhamnumber', 'vienphiid', 'hosobenhanid', 'medicalrecordid_next', 'medicalrecordid_master', 'medicalrecordstatus', 'departmentgroupid', 'departmentid', 'giuong', 'loaibenhanid', 'userid', 'patientid', 'doituongbenhnhanid', 'bhytid', 'lydodenkham', 'yeucaukham', 'thoigianvaovien', 'chandoanvaovien', 'chandoantuyenduoi', 'chandoantuyenduoi_code', 'noigioithieucode', 'chandoanvaovien_code', 'chandoanvaovien_kemtheo', 'chandoanvaovien_kemtheo_code', 'chandoankkb', 'chandoankkb_code', 'chandoanvaokhoa', 'chandoanvaokhoa_code', 'chandoanvaokhoa_kemtheo', 'chandoanvaokhoa_kemtheo_code', 'isthuthuat', 'isphauthuat', 'hinhthucvaovienid', 'backdepartmentid', 'uutienkhamid', 'noigioithieuid', 'vaoviencungbenhlanthu', 'thoigianravien', 'chandoanravien', 'chandoanravien_code', 'chandoanravien_kemtheo', 'chandoanravien_kemtheo_code', 'chandoanravien_kemtheo1', 'chandoanravien_kemtheo_code1', 'chandoanravien_kemtheo2', 'chandoanravien_kemtheo_code2', 'xutrikhambenhid', 'hinhthucravienid', 'ketquadieutriid', 'nextdepartmentid', 'nexthospitalid', 'istaibien', 'isbienchung', 'giaiphaubenhid', 'lydovaovien', 'vaongaythucuabenh', 'quatrinhbenhly', 'tiensubenh_banthan', 'tiensubenh_giadinh', 'khambenh_toanthan', 'khambenh_mach', 'khambenh_nhietdo', 'khambenh_huyetap_low', 'khambenh_huyetap_high', 'khambenh_nhiptho', 'khambenh_cannang', 'khambenh_chieucao', 'khambenh_vongnguc', 'khambenh_vongdau', 'khambenh_bophan', 'tomtatkqcanlamsang', 'chandoanbandau', 'daxuly', 'tomtatbenhan', 'chandoankhoakhambenh', 'daxulyotuyenduoi', 'medicalrecordremark', 'lastaccessdate', 'canlamsangstatus', 'version', 'sync_flag', 'update_flag', 'lastuserupdated', 'lasttimeupdated', 'keylock', 'cv_chuyenvien_hinhthucid', 'cv_chuyenvien_lydoid', 'cv_chuyendungtuyen', 'cv_chuyenvuottuyen', 'xetnghiemcanthuchienlai', 'loidanbacsi', 'nextbedrefid', 'nextbedrefid_nguoinha', 'chandoanbandau_code', 'thoigianchuyenden', 'khambenh_thilucmatphai', 'khambenh_thilucmattrai', 'khambenh_klthilucmatphai', 'khambenh_klthilucmattrai', 'khambenh_nhanapmatphai', 'khambenh_nhanapmattrai'];

    protected $guarded = ['id'];

    public $timestamps = false;

}
