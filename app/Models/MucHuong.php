<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MucHuong extends Model
{
    protected $table = 'muc_huong';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
