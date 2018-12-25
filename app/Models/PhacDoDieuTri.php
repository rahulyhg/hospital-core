<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhacDoDieuTri extends Model
{
    protected $table = 'phac_do_dieu_tri';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
