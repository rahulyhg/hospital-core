<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $khoa_id
 * @property int $so_phong
 * @property string $ma_nhom
 * @property string $ten_phong
 * @property int $loai_phong
 * @property int $loai_benh_an
 * @property int $trang_thai_hoat_dong
 * @property string $ten_nhom
 */
class Phong extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'phong';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    //protected $fillable = ['departmentgroupid', 'departmentgroupid_noitru', 'medicinestoreid', 'appid', 'departmentnumber', 'departmentcode', 'madaudocthe', 'departmentname', 'departmentnameck', 'departmenttype', 'loaibenhanid', 'isphongluu', 'iskhonghoatdong', 'departmentremark', 'listdepartmentlinhthuoc', 'listdepartmentphongchidinh', 'thoigianthuchien', 'sothutuphongkham', 'maphongkham', 'chuyenkhoaphongkham', 'barcodeformat', 'version', 'sync_flag', 'update_flag', 'isphongcapcuu', 'sothutuuutien_max', 'sothutuuutien_lock', 'sothutuuutien_lastupdate', 'departmentcode_byt'];

    protected $guarded = ['id'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

}
