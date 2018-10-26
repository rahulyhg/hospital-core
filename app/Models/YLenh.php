<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YLenh extends Model
{
    protected $table = 'y_lenh';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
