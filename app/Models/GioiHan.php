<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GioiHan extends Model
{
    protected $table = 'gioi_han';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
