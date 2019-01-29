<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HsbaPhongKham extends Model
{
    protected $table = 'hsba_phong_kham';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
