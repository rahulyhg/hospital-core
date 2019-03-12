<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhongGiuongChiTiet extends Model
{
    protected $table = 'phong_giuong_chi_tiet';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
