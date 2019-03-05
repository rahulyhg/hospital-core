<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiuongBenh extends Model
{
    protected $table = 'giuong_benh';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
