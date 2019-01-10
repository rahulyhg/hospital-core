<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KetQuaYLenh extends Model
{
    protected $table = 'ket_qua_y_lenh';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
