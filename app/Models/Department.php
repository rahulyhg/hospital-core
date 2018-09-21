<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $departmentid
 * @property int $departmentgroupid
 * @property int $departmentgroupid_noitru
 * @property int $medicinestoreid
 * @property int $appid
 * @property int $departmentnumber
 * @property string $departmentcode
 * @property string $madaudocthe
 * @property string $departmentname
 * @property string $departmentnameck
 * @property int $departmenttype
 * @property int $loaibenhanid
 * @property int $isphongluu
 * @property int $iskhonghoatdong
 * @property string $departmentremark
 * @property string $listdepartmentlinhthuoc
 * @property string $listdepartmentphongchidinh
 * @property string $thoigianthuchien
 * @property int $sothutuphongkham
 * @property string $maphongkham
 * @property string $chuyenkhoaphongkham
 * @property string $barcodeformat
 * @property string $version
 * @property int $sync_flag
 * @property int $update_flag
 * @property int $isphongcapcuu
 * @property int $sothutuuutien_max
 * @property int $sothutuuutien_lock
 * @property string $sothutuuutien_lastupdate
 * @property string $departmentcode_byt
 */
class Department extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'department';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'departmentid';

    /**
     * @var array
     */
    //protected $fillable = ['departmentgroupid', 'departmentgroupid_noitru', 'medicinestoreid', 'appid', 'departmentnumber', 'departmentcode', 'madaudocthe', 'departmentname', 'departmentnameck', 'departmenttype', 'loaibenhanid', 'isphongluu', 'iskhonghoatdong', 'departmentremark', 'listdepartmentlinhthuoc', 'listdepartmentphongchidinh', 'thoigianthuchien', 'sothutuphongkham', 'maphongkham', 'chuyenkhoaphongkham', 'barcodeformat', 'version', 'sync_flag', 'update_flag', 'isphongcapcuu', 'sothutuuutien_max', 'sothutuuutien_lock', 'sothutuuutien_lastupdate', 'departmentcode_byt'];

    protected $guarded = ['departmentid'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

}
