<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DieuTri extends Model
{
    protected $table = 'dieu_tri';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
