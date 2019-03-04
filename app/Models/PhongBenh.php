<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhongBenh extends Model
{
    protected $table = 'phong_benh';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
