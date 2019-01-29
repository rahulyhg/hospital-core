<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuYLenh extends Model
{
    protected $table = 'phieu_y_lenh';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
